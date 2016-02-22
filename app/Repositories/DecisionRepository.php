<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 12.02.16
 * Time: 18:04
 */

namespace App\Repositories;

use App\Models\DecisionTable;
use App\Models\DecisionHistory;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DecisionRepository
{
    public function all($size = null)
    {
        return DecisionTable::paginate(intval($size));
    }

    public function get($id)
    {
        return DecisionTable::findById($id)->toArray();
    }

    public function create($values)
    {
        return DecisionTable::create($values)->toArray();
    }

    public function cloneModel($id)
    {
        $values = DecisionTable::findById($id)->getAttributes();
        unset($values[DecisionTable::PRIMARY_KEY]);

        return DecisionTable::create($values)->toArray();
    }

    public function update($id, $values)
    {
        return DecisionTable::findById($id)->fill($values)->save()->toArray();
    }

    public function delete($id)
    {
        return DecisionTable::findById($id)->delete();
    }

    public function history($size = null, $table_id = null)
    {
        if ($table_id) {
            $query = DecisionHistory::where('table_id', new \MongoId($table_id));
            if ($query->count() <= 0) {
                $e = new ModelNotFoundException;
                $e->setModel(DecisionTable::class);
                throw $e;
            }
            $paginator = $query->paginate(intval($size));
        } else {
            $paginator = DecisionHistory::paginate(intval($size));
        }

        return $paginator;
    }

    public function historyItem($id)
    {
        return DecisionHistory::findById($id)->toArray();
    }

    public function consumerHistory($size = null)
    {
        return DecisionHistory::paginate(intval($size));
    }

    public function consumerHistoryItem($id)
    {
        return DecisionHistory::findById($id)->toConsumerArray();
    }
}
