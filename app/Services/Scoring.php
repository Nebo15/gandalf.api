<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 15.02.16
 * Time: 17:55
 */

namespace App\Services;

use App\Models\DecisionTable;
use App\Models\Condition;
use App\Models\DecisionHistory;
use App\Repositories\DecisionRepository;
use Illuminate\Contracts\Validation\ValidationException;

class Scoring
{
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

        # crooked nail. Maybe you should write your own ODM?
        $scoring_data = [
            'table_id' => new \MongoId($decision->getId()),
            'title' => $decision->title,
            'description' => $decision->description,
            'default_decision' => $decision->default_decision,
            'fields' => $decision->fields()->toArray(),
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
                $this->checkCondition($condition, $values[$condition->field_key]);
                if (!$condition->matched) {
                    $conditions_matched = false;

                } elseif (!$final_decision) {
                    $final_decision = $rule->than;
                }
                $scoring_rule['conditions'][] = $condition->getAttributes();
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
        switch ($condition->condition) {
            case '$eq':
                $matched = $condition->value === $value;
                break;
            case '$ne':
                $matched = $condition->value !== $value;
                break;
            case '$gt':
                $matched = $condition->value > $value;
                break;
            case '$gte':
                $matched = $condition->value >= $value;
                break;
            case '$lt':
                $matched = $condition->value < $value;
                break;
            case '$lte':
                $matched = $condition->value <= $value;
                break;
            case '$in':
                $matched = in_array($value, array_map('trim', explode(',', $condition->value)));
                break;
            case '$nin':
                $matched = !in_array($value, array_map('trim', explode(',', $condition->value)));
                break;
            default:
                throw new \Exception('Undefined condition rule ' . $condition->condition);
        }

        $condition->matched = $matched;
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
