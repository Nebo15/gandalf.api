<?php
/*
 * This code was generated automatically by Nebo15/REST
 */

namespace App\Repositories;

use App\Models\Decision;
use MongoDB\BSON\Regex;
use MongoDB\Driver\Query;
use MongoDB\BSON\ObjectID;
use MongoDB\Driver\Manager;
use MongoDB\BSON\UTCDatetime;
use Nebo15\REST\AbstractRepository;
use Nebo15\LumenApplicationable\ApplicationableHelper;
use Nebo15\LumenApplicationable\Contracts\Applicationable;

/**
 * Class TablesRepository
 * @package App\Repositories
 * @method \App\Models\Table read($id)
 * @method \App\Models\Table getModel()
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
                $where[$field] = new Regex($filter, 'i');
            }
        }
        if (!empty($filters['matching_type'])) {
            $where['matching_type'] = $filters['matching_type'];
        }
        $where['applications'] = ApplicationableHelper::getApplicationId();

        return $this->getModel()->query()->where($where)->paginate($size);
    }

    public function createOrUpdate($values, $id = null)
    {
        /** @var \App\Models\Table $model */
        $model = $id ? $this->read($id) : $this->getModel()->newInstance();
        if ($model instanceof Applicationable) {
            ApplicationableHelper::addApplication($model);
        }
        $model->fill($values);
        if (isset($values['fields'])) {
            $model->setFields($values['fields']);
        }
        if (isset($values['variants'])) {
            $model->setVariants($values['variants']);
        }
        $model->save();

        return $model;
    }

    public function analyzeTableDecisions($table_id, $variant_id)
    {
        $table = $this->read($table_id);
        $mongo = new Manager(sprintf("mongodb://%s:%d", env('DB_HOST'), env('DB_PORT')));
        $query = new Query(
            [
            'table._id' => new ObjectID($table_id),
            'table.variant._id' => new ObjectId($variant_id),
            'applications' => ApplicationableHelper::getApplicationId(),
            'created_at' => ['$gte' => new UTCDatetime($table->updated_at->timestamp * 1000)]
            ],
            ['projection' => ['rules' => 1]]
        );
        $decisions = $mongo->executeQuery(env('DB_DATABASE') . '.' . (new Decision)->getTable(), $query)->toArray();
        $map = [];

        if (($decisionsAmount = count($decisions)) > 0) {
            foreach ($decisions as $decision) {
                $rules = $decision->rules;

                foreach ($rules as $rule) {
                    if (!isset($rule->_id)) {
                        # ignore old decisions without Rule._id
                        continue;
                    }
                    $ruleIndex = strval($rule->_id);
                    foreach ($rule->conditions as $condition) {
                        $index = "$ruleIndex@" . strval($condition->_id);
                        if (!isset($map[$index])) {
                            $map[$index] = ['matched' => 0, 'requests' => 0];
                        }

                        if ($condition->matched === true) {
                            $map[$index]['matched']++;
                        }
                        $map[$index]['requests']++;
                    }
                    if (!isset($map[$ruleIndex])) {
                        $map[$ruleIndex] = ['matched' => 0, 'requests' => 0];
                    }
                    $map[$ruleIndex]['requests']++;
                    if ($rule->than === $rule->decision) {
                        $map[$ruleIndex]['matched']++;
                    }
                }
            }
        }

        $variant = $table->getVariantForCheck($variant_id);
        foreach ($variant->rules as $rule) {
            $ruleIndex = $rule->_id;
            foreach ($rule->conditions as $condition) {
                $index = "$ruleIndex@" . strval($condition->_id);
                if (array_key_exists($index, $map)) {
                    $condition->probability = round($map[$index]['matched'] / $map[$index]['requests'], 5);
                } else {
                    $condition->probability = null;
                }
                $condition->requests = array_key_exists($index, $map) ? $map[$index]['requests'] : 0;
                $rule->conditions()->associate($condition);
            }
            $ruleHasRequests = array_key_exists($ruleIndex, $map);
            $rule->probability = $ruleHasRequests ?
                round($map[$ruleIndex]['matched'] / $map[$ruleIndex]['requests'], 5) :
                0;
            $rule->requests = $ruleHasRequests ? $map[$ruleIndex]['requests'] : 0;
            $variant->rules()->associate($rule);
        }
        $clonedTable = clone $table;
        $clonedTable->variants = [];
        $clonedTable->variants()->associate($variant);

        return $clonedTable;
    }
}
