<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 15.02.16
 * Time: 16:47
 */

namespace App\Models;

/**
 * Class ScoringHistory
 * @package App\Models
 * @property string $default_decision
 * @property Rule[] $rules
 * @property Field[] $fields
 */
class ScoringHistory extends Base
{
    protected $visible = ['_id', 'request', 'fields', 'rules', 'default_decision', self::CREATED_AT, self::UPDATED_AT];

    protected $fillable = ['fields', 'request', 'rules', 'default_decision'];

    public function rules()
    {
        return $this->embedsMany('App\Models\Rule');
    }

    public function fields()
    {
        return $this->embedsMany('App\Models\Field');
    }
}
