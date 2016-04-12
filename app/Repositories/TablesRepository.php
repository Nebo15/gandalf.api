<?php
/*
 * This code was generated automatically by Nebo15/REST
 */

namespace App\Repositories;

use App\Models\Decision;
use Nebo15\REST\AbstractRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class TablesRepository
 * @package App\Repositories
 * @method \App\Models\Table read($id)
 * @method \App\Models\Table[] findByIds(array $ids)
 */
class TablesRepository extends AbstractRepository
{
    protected $modelClassName = 'App\Models\Table';

    protected $observerClassName = 'App\Observers\TableObserver';

    public function readListWithFilters(array $filters = [])
    {
        $size = isset($filter['size']) ? $filter['size'] : null;
        if (!$filters) {
            return $this->readList($size);
        }
        $available = ['title', 'description'];

        $where = [];
        foreach ($filters as $field => $filter) {
            if (in_array($field, $available)) {
                $where[$field] = new \MongoRegex("/$filter/i");
            }
        }
        if (!$where) {
            return $this->readList($size);
        }

        return $this->getModel()->query()->where($where)->paginate($size);
    }

    public function createOrUpdate($values, $id = null)
    {
        /** @var \App\Models\Table $model */
        $model = $id ? $this->read($id) : $this->getModel()->newInstance();
        $model->fill($values);
        if (isset($values['fields'])) {
            $model->setFields($values['fields']);
        }
        if (isset($values['rules'])) {
            $model->setRules($values['rules']);
        }
        $model->save();

        return $model;
    }

    public function analyzeTableDecisions($table_id)
    {
        $table = $this->read($table_id);
        $decisions = Decision::where('table._id', $table_id)->getQuery()->get(['rules']);
        $map = [];

        if (($decisionsAmount = count($decisions)) > 0) {
            for ($i = 0; $i < $decisionsAmount; $i++) {
                $rules = $decisions[$i]['rules'];
                $decisions[$i] = null;
                $ruleIndex = 0;
                foreach ($rules as $rule) {
                    $conditionIndex = 0;
                    foreach ($rule['conditions'] as $condition) {
                        $index = "$ruleIndex@$conditionIndex";
                        if (!isset($map[$index])) {
                            $map[$index] = ['matched' => 0, 'requests' => 0];
                        }

                        if ($condition['matched'] === true) {
                            $map[$index]['matched']++;
                        }
                        $map[$index]['requests']++;

                        $conditionIndex++;
                    }
                    if (!isset($map[$ruleIndex])) {
                        $map[$ruleIndex] = ['matched' => 0, 'requests' => 0];
                    }
                    $map[$ruleIndex]['requests']++;
                    if ($rule['than'] === $rule['decision']) {
                        $map[$ruleIndex]['matched']++;
                    }

                    $ruleIndex++;
                }
            }
        }

        $ruleIndex = 0;
        foreach ($table->rules as $rule) {
            $conditionIndex = 0;
            foreach ($rule->conditions as $condition) {
                $index = "$ruleIndex@$conditionIndex";
                if (array_key_exists($index, $map)) {
                    $condition->probability = round($map[$index]['matched'] / $map[$index]['requests'], 5);
                } else {
                    $condition->probability = null;
                }
                $condition->requests = array_key_exists($index, $map) ? $map[$index]['requests'] : 0;
                $rule->conditions()->associate($condition);

                $conditionIndex++;
            }
            $ruleHasRequests = array_key_exists($ruleIndex, $map);
            $rule->probability = $ruleHasRequests ?
                round($map[$ruleIndex]['matched'] / $map[$ruleIndex]['requests'], 5) :
                0;
            $rule->requests = $ruleHasRequests ? $map[$ruleIndex]['requests'] : 0;
            $ruleIndex++;
            $table->rules()->associate($rule);
        }

        return $table;
    }
}
