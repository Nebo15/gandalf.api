<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 15.02.16
 * Time: 16:47
 */

namespace App\Models;

/**
 * Class DecisionHistory
 * @package App\Models
 * @property string $title
 * @property string $description
 * @property string $default_decision
 * @property string $final_decision
 * @property array $request
 * @property Rule[] $rules
 * @property Field[] $fields
 * @method static DecisionHistory findById($id)
 * @method DecisionHistory save(array $options = [])
 * @method static \Illuminate\Pagination\LengthAwarePaginator paginate($perPage = 15, $columns = ['*'], $pageName = 'page', $page = null)
 */
class DecisionHistory extends Base
{
    protected $visible = [
        '_id',
        'request',
        'table',
        'title',
        'description',
        'fields',
        'rules',
        'default_decision',
        'final_decision',
        self::CREATED_AT,
        self::UPDATED_AT
    ];

    protected $fillable = [
        'title',
        'description',
        'table',
        'fields',
        'request',
        'rules',
        'default_decision',
        'final_decision'
    ];

    protected $perPage = 20;

    public function rules()
    {
        return $this->embedsMany('App\Models\Rule');
    }

    public function fields()
    {
        return $this->embedsMany('App\Models\Field');
    }

    public function toConsumerArray()
    {
        return [
            '_id' => $this->getId(),
            'table' => $this->getTableArray(),
            'title' => $this->title,
            'description' => $this->description,
            'final_decision' => $this->final_decision,
            'request' => $this->request,
            'rules' => $this->rules()->get()->map(function (Rule $rule) {
                return [
                    'title' => $rule->title,
                    'description' => $rule->description,
                    'decision' => $rule->decision
                ];
            })->toArray()
        ];
    }

    public function toArray()
    {
        # Cause property table are
        $data = parent::toArray();
        $data['table'] = $this->getTableArray();

        return $data;
    }

    public function getTableArray()
    {
        $data = $this->getAttribute('table');
        $data['_id'] = strval($data['_id']);

        return $data;
    }
}
