<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 15.02.16
 * Time: 16:47
 */

namespace App\Models;

/**
 * Class Decision
 * @package App\Models
 * @property string $title
 * @property string $description
 * @property string $default_decision
 * @property string $final_decision
 * @property array $request
 * @property array $table
 * @property array $group
 * @property Rule[] $rules
 * @property Field[] $fields
 * @method static Decision findById($id)
 * @method Decision save(array $options = [])
 * @method static \Illuminate\Pagination\LengthAwarePaginator paginate($perPage = 15, $columns = ['*'], $pageName = 'page', $page = null)
 */
class Decision extends Base
{
    protected $visible = [
        '_id',
        'request',
        'table',
        'group',
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
        'group',
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
            self::CREATED_AT => $this->getAttribute(self::CREATED_AT),
            self::UPDATED_AT => $this->getAttribute(self::UPDATED_AT),
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
        # Cause property table have MongoID object
        $data = parent::toArray();
        $data['table'] = $this->getTableArray();
        $data['group'] = $this->getGroupAttribute($data['group']);

        return $data;
    }

    public function getTableArray()
    {
        $data = $this->getAttribute('table');
        $data['_id'] = strval($data['_id']);

        return $data;
    }

    public function setGroupAttribute($value)
    {
        if (isset($value['_id']) and !($value['_id'] instanceof \MongoId)) {
            $value['_id'] = new \MongoId($value['_id']);
        }

        $this->attributes['group'] = $value;
    }

    public function getGroupAttribute($value)
    {
        if ($value) {
            $value['_id'] = strval($value['_id']);
        }

        return $value;
    }
}
