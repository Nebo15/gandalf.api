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
 */
class TablesRepository extends AbstractRepository
{
    protected $modelClassName = 'App\Models\Table';

    protected $observerClassName = 'App\Observers\TableObserver';

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
            $query = Decision::where('table._id', new \MongoId($table_id));
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

    public function getConsumerDecisions($size = null)
    {
        return Decision::orderBy(Decision::CREATED_AT, 'DESC')->paginate(intval($size));
    }

    public function getConsumerDecision($id)
    {
        return Decision::findById($id)->toConsumerArray();
    }
}
