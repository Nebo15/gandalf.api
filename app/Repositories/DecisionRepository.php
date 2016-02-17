<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 12.02.16
 * Time: 18:04
 */

namespace App\Repositories;

use App\Models\Decision;
use App\Models\DecisionHistory;

class DecisionRepository
{
    public function all($size = null)
    {
        return Decision::paginate(intval($size));
    }

    public function get($id)
    {
        return Decision::findById($id)->toArray();
    }

    public function create($values)
    {
        return Decision::create($values)->toArray();
    }

    public function update($id, $values)
    {
        return Decision::findById($id)->fill($values)->save()->toArray();
    }

    public function delete($id)
    {
        return Decision::findById($id)->delete();
    }

    public function history($size = null)
    {
        return DecisionHistory::paginate(intval($size));
    }

    public function historyItem($id)
    {
        return DecisionHistory::findById($id)->toArray();
    }

    public function consumerHistory($size = null)
    {

    }

    public function consumerHistoryItem($id)
    {
        return DecisionHistory::findById($id)->shortApiView();
    }

    public function getDecision()
    {
        return Decision::first();
    }
}
