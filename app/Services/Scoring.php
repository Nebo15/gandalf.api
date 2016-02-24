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

class Scoring
{
    private $presets = [];

    private $decisionRepository;

    public function __construct(DecisionRepository $decisionRepository)
    {
        $this->decisionRepository = $decisionRepository;
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
            'table_id' => new \MongoId($decision->getId()),
            'title' => $decision->title,
            'description' => $decision->description,
            'default_decision' => $decision->default_decision,
            'fields' => $fields->toArray(),
            'rules' => [],
            'request' => $values,
            'webhook' => isset($values['webhook']) ? $values['webhook'] : null
        ];
        $final_decision = null;

        /** @var \App\Models\Rule $rule */
        foreach ($decision->rules()->get() as $rule) {
            $scoring_rule = [
                'than' => $rule->than,
                'description' => $rule->description,
                'conditions' => []
            ];
            $conditions_matched = true;
            foreach ($rule->conditions as $condition) {

                $this->checkCondition(
                    $condition,
                    $this->prepareFieldPreset(
                        $fields->where('alias', $condition->field_alias)->first(),
                        $values[$condition->field_alias]
                    )
                );

                if (!$condition->matched) {
                    $conditions_matched = false;
                }
                $scoring_rule['conditions'][] = $condition->getAttributes();
            }
            if (!$final_decision and $conditions_matched) {
                $final_decision = $rule->than;
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
        $condition->matched = $this->checkConditionValue($condition->condition, $condition->value, $value);
    }

    private function prepareFieldPreset(Field $field, $value)
    {
        if (array_key_exists($field->alias, $this->presets)) {
            $value = $this->presets[$field->alias];

        } elseif ($preset = $field->preset and $preset->condition) {
            $value = $this->checkConditionValue($preset->condition, $preset->value, $value);
            $this->presets[$field->alias] = $value;
        }

        return $value;
    }

    private function checkConditionValue($condition, $condition_value, $field_value)
    {
        switch ($condition) {
            case '$eq':
                $matched = $condition_value === $field_value;
                break;
            case '$ne':
                $matched = $condition_value !== $field_value;
                break;
            case '$gt':
                $matched = $field_value > $condition_value;
                break;
            case '$gte':
                $matched = $field_value >= $condition_value;
                break;
            case '$lt':
                $matched = $field_value < $condition_value;
                break;
            case '$lte':
                $matched = $field_value <= $condition_value;
                break;
            case '$in':
                $matched = in_array($field_value, array_map('trim', explode(',', $condition_value)));
                break;
            case '$nin':
                $matched = !in_array($field_value, array_map('trim', explode(',', $condition_value)));
                break;
            default:
                throw new \Exception("Undefined condition rule '$condition'");
        }

        return $matched;
    }

    private function createValidationRules(DecisionTable $decision)
    {
        $rules = ['webhook' => 'sometimes|required|url'];
        if ($fields = $decision->fields) {
            foreach ($fields as $item) {
                $rules[$item->key] = 'required' . $this->getValidationRuleByType($item->type);
            }
        }

        return $rules;
    }

    private function getValidationRuleByType($type)
    {
        return $type == 'bool' ? '|boolean' : '';
    }
}
