<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 12.02.16
 * Time: 18:04
 */

namespace App\Repositories;

use App\Models\Decision;

class DecisionRepository
{
    public function all()
    {
        return Decision::all()->toArray();
    }

    public function update($values)
    {
        $decision = $this->getDecision();
        $decision->fill($values)->save();

        return $decision->toArray();
    }


    /**
     * @return Decision
     */
    public function getDecision()
    {
        return Decision::first();
    }
}
