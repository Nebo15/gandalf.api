<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 12.02.16
 * Time: 17:39
 */

namespace App\Models;

/**
 * Class DecisionTable
 * @package App\Models
 * @property string $title
 * @property string $description
 * @property string $default_decision
 * @property Rule[] $rules
 * @property Field[] $fields
 * @method static DecisionTable findById($id)
 * @method static DecisionTable create(array $attributes = [])
 * @method DecisionTable save(array $options = [])
 * @method static \Illuminate\Pagination\LengthAwarePaginator paginate($perPage = 15, $columns = ['*'], $pageName = 'page', $page = null)
 */
class DecisionTable extends Base
{
    protected $visible = ['_id', 'title', 'description', 'default_decision', 'rules', 'fields'];

    protected $fillable = ['title', 'description', 'default_decision'];

    protected $perPage = 20;

    protected function getArrayableRelations()
    {
        return [
            'fields' => $this->fields,
            'rules' => $this->rules
        ];
    }

    public function rules()
    {
        return $this->embedsMany('App\Models\Rule');
    }

    public function fields()
    {
        return $this->embedsMany('App\Models\Field');
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

    public function setFields($fields)
    {
        $this->fields()->delete();
        foreach ($fields as $field) {
            $this->fields()->associate(new Field($field));
        }

        return $this;
    }

    public function toListArray()
    {
        return [
            '_id' => $this->getId(),
            'title' => $this->title,
            'description' => $this->description,
            'default_decision' => $this->default_decision,
        ];
    }
}
