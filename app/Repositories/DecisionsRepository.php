<?php
/*
 * This code was generated automatically by Nebo15/REST
 */

namespace App\Repositories;

use App\Models\Decision;
use Nebo15\REST\AbstractRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Contracts\Validation\ValidationException;

/**
 * Class DecisionsRepository
 * @package App\Repositories
 * @method Decision read($id)
 */
class DecisionsRepository extends AbstractRepository
{
    protected $modelClassName = 'App\Models\Decision';

    public function getDecisions($size = null, $table_id = null, $variant_id = null)
    {
        /** @var \Jenssegers\Mongodb\Eloquent\Builder $query */
        if ($table_id) {
            $query = $this->getModel()->query()->where('table._id', $table_id);
            if ($query->count() <= 0) {
                $e = new ModelNotFoundException;
                $e->setModel(Decision::class);
                throw $e;
            }
            $query = $query->orderBy(Decision::CREATED_AT, 'DESC');
        } elseif ($variant_id) {
            $query = $this->getModel()->query()->where('table.variant._id', $variant_id);
        } else {
            $query = Decision::orderBy(Decision::CREATED_AT, 'DESC');
        }

        return $this->paginateQuery($query, $size);
    }

    public function getConsumerDecision($id)
    {
        return $this->read($id)->toConsumerArray();
    }

    public function updateMeta($id, $meta)
    {
        $decision = $this->read($id);

        $i = 0;
        $rules = [];
        $values = [];
        foreach ($meta as $key => $value) {
            $values["key_$i"] = $key;
            $values["key_{$i}_value"] = $value;
            $rules["key_$i"] = 'required|max:100|alpha_dash';
            $rules["key_{$i}_value"] = 'required|max:500|regex:/.+/';
            $i++;
        }
        $values['meta_keys_amount'] = count($rules) / 2;
        $rules['meta_keys_amount'] = 'metaKeysAmount';

        $validator = \Validator::make($values, $rules);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $decision->meta = $meta;

        return $decision->save();
    }
}
