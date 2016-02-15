<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 15.02.16
 * Time: 17:55
 */

namespace App\Services;

use App\Models\Condition;
use App\Models\Decision;
use App\Repositories\DecisionRepository;
use Coduo\PHPMatcher\Factory\SimpleFactory;


class Scoring
{
    private $matcher;
    private $decisionRepository;

    public function __construct(DecisionRepository $decisionRepository)
    {
        $this->decisionRepository = $decisionRepository;
        $this->matcher = (new SimpleFactory)->createMatcher();
    }

    public function check($values)
    {
        $decision = $this->decisionRepository->getDecision();
        Validator::make($values, $this->createValidationRules($decision));

        foreach ($decision->rules as $rule) {
            $conditions_matched = false;
            foreach ($rule->conditions as $condition) {
                $this->checkCondition($condition->condition, $values[$condition->field_alias]);
            }
            $rule->result = $conditions_matched ? $rule->decision : null;
        }
    }

    /**
     * @param Condition $condition
     * @param $value
     */
    private function checkCondition($condition, $value)
    {
        $this->matcher->startsWith();
        $condition->matched = true;
    }

    private function createValidationRules(Decision $decision)
    {
        $rules = [];
        if ($fields = $decision->fields) {
            foreach ($fields as $item) {
                $rules[$item->field_alias] = 'required|' . $this->getValidationRuleByType($item->type);
            }
        }

        return $rules;
    }

    private function getValidationRuleByType($type)
    {
        # ToDo: write code

        return 'string';
    }
}
