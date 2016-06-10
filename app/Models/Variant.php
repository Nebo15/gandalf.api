<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 25.05.16
 * Time: 13:35
 */

namespace App\Models;

/**
 * Class Variant
 * @package App\Models
 * @property string $title
 * @property string $description
 * @property string $default_title
 * @property string $default_description
 * @property string $default_decision
 * @property Rule[] $rules
 */
class Variant extends Base
{
    protected $attributes = [
        'title' => '',
        'description' => '',
        'default_title' => '',
        'default_description' => '',
        'probability' => 0,
    ];

    protected $visible = [
        '_id',
        'title',
        'description',
        'default_decision',
        'default_title',
        'default_description',
        'probability',
        'rules',
        'fields',
    ];

    protected $fillable = [
        '_id',
        'title',
        'description',
        'default_title',
        'default_description',
        'default_decision',
        'matching_type',
        'probability',
    ];

    protected $casts = [
        '_id' => 'string',
        'title',
        'description',
        'default_title' => 'string',
        'default_description' => 'string',
    ];

    protected function getArrayableRelations()
    {
        return [
            'rules' => $this->rules,
        ];
    }

    public function rules()
    {
        return $this->embedsMany('App\Models\Rule');
    }

    public function setRules($rules)
    {
        $this->rules()->delete();
        foreach ($rules as $rule) {
            $ruleModel = new Rule($rule);
            if (isset($rule['conditions'])) {
                $ruleModel->setConditions($rule['conditions']);
            }
            $this->rules()->associate($ruleModel);
        }

        return $this;
    }
}
