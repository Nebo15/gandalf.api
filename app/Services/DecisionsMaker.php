<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 15.02.16
 * Time: 17:55
 */

namespace App\Services;

use App\Models\Group;
use App\Models\Table;
use App\Models\Field;
use App\Models\Decision;
use App\Models\Condition;
use App\Models\Tree;
use App\Repositories\GroupsRepository;
use App\Repositories\TablesRepository;
use Illuminate\Contracts\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DecisionsMaker
{
    protected $presets = [];

    protected $conditionsTypes;

    protected $tablesRepository;

    protected $groupsRepository;

    public function __construct(TablesRepository $tablesRepository, GroupsRepository $groupsRepository)
    {
        $this->tablesRepository = $tablesRepository;
        $this->groupsRepository = $groupsRepository;
        $this->conditionsTypes = new ConditionsTypes;
    }

    public function check($tableId, $values, Group $group = null)
    {
        $table = $this->tablesRepository->read($tableId);
        $this->validateFields($table, $values);

        $decisionData = array_merge(
            $this->prepareDecisionData($table, $values, $group),
            $this->makeDecision($table, $values)
        );

        if ($decisionData['webhook']) {
            # create webhook service
        }

        return Decision::create($decisionData)->toConsumerArray();
    }

    protected function validateFields(Table $table, $values)
    {
        $validator = \Validator::make($values, $this->createValidationRules($table));
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    protected function prepareDecisionData(Table $table, $values, Group $group = null, Tree $tree = null)
    {
        foreach (['group', 'tree'] as $item) {
            if ($$item) {
                $$item = [
                    '_id' => $$item->getKey(),
                    'title' => $$item->title,
                    'description' => $$item->description,
                ];
            }
        }

        return [
            'table' => [
                '_id' => new \MongoId($table->getId()),
                'title' => $table->title,
                'description' => $table->description,
                'matching_type' => $table->matching_type,
            ],
            'group' => $group,
            'tree' => $tree,
            'title' => $table->default_title,
            'description' => $table->default_description,
            'default_decision' => $table->default_decision,
            'fields' => $table->fields()->toArray(),
            'rules' => [],
            'request' => $values,
            'webhook' => isset($values['webhook']) ? $values['webhook'] : null
        ];
    }

    protected function makeDecision(Table $table, $values)
    {
        $final_decision = null;
        $fieldsCollection = $table->fields()->get();

        /** @var \App\Models\Rule $rule */
        foreach ($table->rules()->get() as $rule) {
            $decisionsRule = [
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
                $decisionsRule['conditions'][] = $condition;

                $fieldIndex++;
            }
            if ($table->matching_type == 'all') {
                if ($conditions_matched) {
                    $final_decision += floatval($rule->than);
                }
            } else {

                if (!$final_decision and $conditions_matched) {
                    $final_decision = $rule->than;
                    $decisionData['title'] = $rule->title;
                    $decisionData['description'] = $rule->description;
                }
            }

            $decisionsRule['decision'] = $conditions_matched ? $rule->than : null;
            $decisionData['rules'][] = $decisionsRule;
        }
        $decisionData['final_decision'] = $final_decision ?: $table->default_decision;

        return $decisionData;
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
        if (array_key_exists($field->index, $this->presets)) {
            $value = $this->presets[$field->index];

        } elseif ($preset = $field->preset and $preset->condition) {
            $value = $this->conditionsTypes->checkConditionValue($preset->condition, $preset->value, $value);
            $this->presets[$field->index] = $value;
        }

        return $value;
    }

    private function createValidationRules(Table $table)
    {
        $rules = ['webhook' => 'sometimes|required|url'];
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
