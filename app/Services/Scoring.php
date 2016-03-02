<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 15.02.16
 * Time: 17:55
 */

namespace App\Services;

use App\Models\Field;
use App\Models\Condition;
use App\Models\DecisionTable;
use App\Models\DecisionHistory;
use App\Repositories\DecisionRepository;
use Illuminate\Contracts\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Scoring
{
    private $presets = [];

    private $conditionsTypes;

    private $decisionRepository;

    public function __construct(DecisionRepository $decisionRepository)
    {
        $this->decisionRepository = $decisionRepository;
        $this->conditionsTypes = new ConditionsTypes;
    }

    public function check($id, $values)
    {
        $decision = DecisionTable::findById($id);
        $validator = \Validator::make($values, $this->createValidationRules($decision));
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        /** @var \Jenssegers\Mongodb\Relations\EmbedsMany $fields */
        $fields = $decision->fields();

        # crooked nail. Maybe you should write your own ODM?
        $scoring_data = [
            'table' => [
                '_id' => new \MongoId($decision->getId()),
                'title' => $decision->title,
                'description' => $decision->description,
            ],
            'title' => '',
            'description' => '',
            'default_decision' => $decision->default_decision,
            'fields' => $fields->toArray(),
            'rules' => [],
            'request' => $values,
            'webhook' => isset($values['webhook']) ? $values['webhook'] : null
        ];
        $final_decision = null;
        $fieldsCollection = $fields->get();

        /** @var \App\Models\Rule $rule */
        foreach ($decision->rules()->get() as $rule) {
            $scoring_rule = [
                'than' => $rule->than,
                'title' => $rule->title,
                'description' => $rule->description,
                'conditions' => []
            ];
            $conditions_matched = true;
            $fieldIndex = 0;
            foreach ($rule->conditions as $condition) {

                /** @var Field $field */
                $field = $fieldsCollection->offsetGet($fieldIndex);
                if ($field->key != $condition->field_key) {
                    throw new HttpException(
                        500,
                        "Field key '{$field->key}' and condition key '{$condition->field_key}' does not matched"
                    );
                }
                $field->index = $fieldIndex;
                $this->checkCondition($condition, $this->prepareFieldPreset($field, $values[$condition->field_key]));

                if (!$condition->matched) {
                    $conditions_matched = false;
                }
                $condition = $condition->getAttributes();
                unset($condition[Condition::PRIMARY_KEY]);
                $scoring_rule['conditions'][] = $condition;

                $fieldIndex++;
            }
            if (!$final_decision and $conditions_matched) {
                $final_decision = $rule->than;
                $scoring_data['title'] = $rule->title;
                $scoring_data['description'] = $rule->description;
            }

            $scoring_rule['decision'] = $conditions_matched ? $rule->than : null;
            $scoring_data['rules'][] = $scoring_rule;
        }

        $scoring_data['final_decision'] = $final_decision ?: $decision->default_decision;
        if (isset($values['webhook'])) {
            # create webhook service
        }

        return DecisionHistory::create($scoring_data)->toConsumerArray();
    }

    private function checkCondition(Condition $condition, $value)
    {
        $condition->matched = $this->conditionsTypes->checkConditionValue($condition->condition, $condition->value, $value);
    }

    private function prepareFieldPreset(Field $field, $value)
    {
        if (array_key_exists($field->index, $this->presets)) {
            $value = $this->presets[$field->index];

        } elseif ($preset = $field->preset and $preset->condition) {
            $value = $this->conditionsTypes->checkConditionValue($preset->condition, $preset->value, $value);
            $this->presets[$field->index] = $value;
        }

        return $value;
    }

    private function createValidationRules(DecisionTable $decision)
    {
        $rules = ['webhook' => 'sometimes|required|url'];
        if ($fields = $decision->fields) {
            foreach ($fields as $item) {
                $rules[$item->key] = 'required|' . $this->getValidationRuleByType($item->type);
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
