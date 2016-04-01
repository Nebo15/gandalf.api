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

    public function getDecisions($size = null, $table_id = null)
    {
        if ($table_id) {
            $query = Decision::where('table._id', $table_id);
            if ($query->count() <= 0) {
                $e = new ModelNotFoundException;
                $e->setModel(Decision::class);
                throw $e;
            }
            $query = $query->orderBy(Decision::CREATED_AT, 'DESC');
        } else {
            $query = Decision::orderBy(Decision::CREATED_AT, 'DESC');
        }

        return $query->paginate(intval($size));
    }

    public function getDecisionById($id)
    {
        return Decision::findById($id);
    }

    public function analyzeTableDecisions($table_id)
    {
        $table = $this->read($table_id);
        $decisions = Decision::where('table._id', $table_id)->get();
        $map = [];
        /** @var Decision $decision */

        foreach ($decisions as $decision) {
            $rule_index = 0;
            foreach ($decision->rules as $rule) {
                $condition_index = 0;
                foreach ($rule->conditions as $condition) {
                    $index = "$rule_index@$condition_index";
                    if (!isset($map[$index])) {
                        $map[$index] = ['matched' => 0, 'requests' => 0];
                    }

                    if ($condition->matched === true) {
                        $map[$index]['matched']++;
                    }
                    $map[$index]['requests']++;

                    $condition_index++;
                }
                $rule_index++;
            }
        }

        $rule_index = 0;
        foreach ($table->rules as $rule) {
            $condition_index = 0;
            foreach ($rule->conditions as $condition) {
                $index = "$rule_index@$condition_index";
                if (array_key_exists($index, $map)) {
                    $condition->probability = round($map[$index]['matched'] / $map[$index]['requests'], 5);
                } else {
                    $condition->probability = null;
                }
                $condition->requests = $map[$index]['requests'];
                $rule->conditions()->associate($condition);

                $condition_index++;
            }
            $rule_index++;
            $table->rules()->associate($rule);
        }

        return $table;
    }

    public function getConsumerDecisions($size = null)
    {
        return Decision::orderBy(Decision::CREATED_AT, 'DESC')->paginate(intval($size));
    }

    public function getConsumerDecision($id)
    {
        return Decision::findById($id)->toConsumerArray();
    }
}
