<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 15.02.16
 * Time: 17:39
 */

namespace App\Models;

/**
 * Class Rule
 * @package App\Models
 * @property string $title
 * @property string $description
 * @property string $decision
 * @property string $than
 * @property float $probability
 * @property integer $requests
 * @property Condition[] $conditions
 *
 */
class Rule extends Base
{
    protected $visible = ['title', 'decision', 'description', 'than', 'probability', 'requests', 'conditions'];

    protected $fillable = ['title', 'description', 'than'];

    protected $casts = [
        '_id' => 'string',
        'title' => 'string',
        'description' => 'string',
    ];

    protected function getArrayableRelations()
    {
        return ['conditions' => $this->conditions];
    }

    public function conditions()
    {
        return $this->embedsMany('App\Models\Condition');
    }

    public function setConditions($conditions)
    {
        $this->conditions()->delete();
        foreach ($conditions as $condition) {
            $this->conditions()->associate(new Condition($condition));
        }

        return $this;
    }

    public function setThanAttribute($value)
    {
        $this->attributes['than'] = is_float($value) ? round($value, 5) : $value;
    }
}
