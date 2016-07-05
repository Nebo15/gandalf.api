<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 15.02.16
 * Time: 17:55
 */

namespace App\Services;

use App\Models\Table;
use App\Models\Field;
use App\Models\Decision;
use App\Models\Condition;
use \MongoDB\BSON\ObjectID;
use App\Events\Decisions\Make;
use App\Repositories\TablesRepository;
use Illuminate\Contracts\Validation\ValidationException;

class Scoring
{
    private $presets = [];

    private $conditionsTypes;

    private $tablesRepository;

    public function __construct(TablesRepository $tablesRepository)
    {
        $this->tablesRepository = $tablesRepository;
        $this->conditionsTypes = new ConditionsTypes;
    }

    public function check($id, $values, $showMeta = false)
    {
        $table = $this->tablesRepository->read($id);
        $validator = \Validator::make($values, $this->createValidationRules($table));
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        $fields = $table->fields();
        $variant = $table->getVariantForCheck(isset($values['variant_id']) ? $values['variant_id'] : null);

        $scoring_data = [
            'table' => [
                '_id' => new ObjectID($table->getId()),
                'title' => $table->title,
                'description' => $table->description,
                'matching_type' => $table->matching_type,
                'variant' => [
                    '_id' => new ObjectID($variant->getId()),
                    'title' => $variant->title,
                    'description' => $variant->description,
                ]
            ],
            'applications' => $table->getApplications(),
            'title' => $variant->default_title,
            'description' => $variant->default_description,
            'default_decision' => $variant->default_decision,
            'fields' => $fields->toArray(),
            'rules' => [],
            'request' => $values,
        ];
        $final_decision = null;
        $fieldsCollection = $fields->get();

        /** @var \App\Models\Rule $rule */
        foreach ($variant->rules()->get() as $rule) {
            $scoring_rule = [
                '_id' => new ObjectID($rule->_id),
                'than' => $rule->than,
                'title' => $rule->title,
                'description' => $rule->description,
                'conditions' => [],
            ];
            $conditions_matched = true;
            foreach ($rule->conditions as $condition) {
                $fieldKey = $condition->field_key;
                /** @var Field $field */
                $field = $fieldsCollection->filter(function ($item) use ($fieldKey) {
                    return $item->key == $fieldKey;
                })->first();

                if (!$field) {
                    # skip, because field may not be exists
                    continue;
                }
                $this->checkCondition($condition, $this->prepareFieldPreset($field, $values[$condition->field_key]));

                if (!$condition->matched) {
                    $conditions_matched = false;
                }
                $condition = $condition->getAttributes();
                $scoring_rule['conditions'][] = $condition;
            }
            if ($table->matching_type == 'scoring') {
                if ($conditions_matched) {
                    $final_decision += floatval($rule->than);
                }
            } else {
                if (!$final_decision and $conditions_matched) {
                    $final_decision = $rule->than;
                    $scoring_data['title'] = $rule->title;
                    $scoring_data['description'] = $rule->description;
                }
            }

            $scoring_rule['decision'] = $conditions_matched ? $rule->than : null;
            $scoring_data['rules'][] = $scoring_rule;
        }
        $scoring_data['final_decision'] = $final_decision ?: $variant->default_decision;

        $decision = (new Decision())->fill($scoring_data)->save();
        \Event::fire(new Make($decision));
        $response = $decision->toConsumerArray();
        if (!$showMeta) {
            unset($response['rules']);
        }

        return $response;
    }

    private function checkCondition(Condition $condition, $value)
    {
        $condition->matched = $this->conditionsTypes->checkConditionValue(
            $condition->condition,
            $condition->value,
            $value
        );
    }

    private function prepareFieldPreset(Field $field, $value)
    {
        if (array_key_exists($field->key, $this->presets)) {
            $value = $this->presets[$field->key];
        } elseif ($preset = $field->preset and $preset->condition) {
            $value = $this->conditionsTypes->checkConditionValue($preset->condition, $preset->value, $value);
            $this->presets[$field->key] = $value;
        }

        return $value;
    }

    private function createValidationRules(Table $table)
    {
        $rules = ['variant_id' => 'sometimes|required|MongoId'];
        if ($fields = $table->fields) {
            foreach ($fields as $item) {
                $rules[$item->key] = 'present|' . $this->getValidationRuleByType($item->type);
            }
        }

        return $rules;
    }

    private function getValidationRuleByType($type)
    {
        switch (strtolower($type)) {
            case 'number':
            case 'integer':
            case 'numeric':
                $rule = 'numeric';
                break;

            case 'bool':
            case 'boolean':
                $rule = 'boolean';
                break;

            default:
                $rule = 'string';
        }

        return $rule;
    }
}
