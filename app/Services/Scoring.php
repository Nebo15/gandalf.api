<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 15.02.16
 * Time: 17:55
 */

namespace App\Services;

use App\Models\Base;
use App\Models\Condition;
use App\Models\Decision;
use App\Models\ScoringHistory;
use App\Repositories\DecisionRepository;
use Illuminate\Contracts\Validation\ValidationException;

class Scoring
{
    private $matcher;
    private $decisionRepository;

    public function __construct(DecisionRepository $decisionRepository)
    {
        $this->decisionRepository = $decisionRepository;
    }

    public function check($values)
    {
        $decision = $this->decisionRepository->getDecision();
        $validator = \Validator::make($values, $this->createValidationRules($decision));
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        foreach ($decision->rules as $rule) {
            $conditions_matched = true;
            foreach ($rule['conditions'] as $condition) {
                $this->checkCondition($condition, $values[$condition['field_alias']]);
                if (!$condition['matched']) {
                    $conditions_matched = false;
                }
            }
            $rule['result'] = $conditions_matched ? $rule['decision'] : null;
        }

        return $this->createHistory($decision, $values);
    }

    private function createHistory(Decision $decision, $values)
    {
        $data = $decision->toArray();
        $data['request'] = $values;
        unset($data[Base::PRIMARY_KEY]);

        return ScoringHistory::create($data);
    }

    private function checkCondition(&$condition, $value)
    {
        switch ($condition['condition']) {
            case '$eq':
                $matched = $condition['value'] === $value;
                break;
            case '$ne':
                $matched = $condition['value'] !== $value;
                break;
            case '$gt':
                $matched = $condition['value'] > $value;
                break;
            case '$gte':
                $matched = $condition['value'] >= $value;
                break;
            case '$lt':
                $matched = $condition['value'] < $value;
                break;
            case '$lte':
                $matched = $condition['value'] <= $value;
                break;
            case '$in':
                $matched = in_array($value, array_map('trim', explode(',', $condition['value'])));
                break;
            case '$nin':
                $matched = !in_array($value, array_map('trim', explode(',', $condition['value'])));
                break;
            default:
                throw new \Exception('Undefined condition rule ' . $condition['condition']);
        }

        $condition['matched'] = $matched;
    }

    private function createValidationRules(Decision $decision)
    {
        $rules = [];
        if ($fields = $decision->fields) {
            foreach ($fields as $item) {
                $rules[$item['alias']] = 'required' . $this->getValidationRuleByType($item['type']);
            }
        }

        return $rules;
    }

    private function getValidationRuleByType($type)
    {
        # ToDo: write code

        return '';
    }
}
