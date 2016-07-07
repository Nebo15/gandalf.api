<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 15.02.16
 * Time: 16:47
 */

namespace App\Models;

use Nebo15\LumenApplicationable\Contracts\Applicationable;
use Nebo15\LumenApplicationable\Traits\ApplicationableTrait;

/**
 * Class Decision
 * @package App\Models
 * @property string $title
 * @property string $description
 * @property string $default_decision
 * @property string $final_decision
 * @property string $application
 * @property array $request
 * @property array $table
 * @property array $meta
 * @property array $group
 * @property Rule[] $rules
 * @property Field[] $fields
 * @method static Decision findById($id)
 * @method Decision save(array $options = [])
 * @method static \Illuminate\Pagination\LengthAwarePaginator paginate($perPage = 15, $columns = ['*'], $pageName = 'page', $page = null)
 */
class Decision extends Base implements Applicationable
{
    use ApplicationableTrait;

    protected $visible = [
        '_id',
        'title',
        'description',
        'meta',
        'table',
        'application',
        'fields',
        'request',
        'rules',
        'default_decision',
        'final_decision',
        self::CREATED_AT,
        self::UPDATED_AT,
    ];

    protected $fillable = [
        'title',
        'description',
        'meta',
        'table',
        'application',
        'fields',
        'request',
        'rules',
        'default_decision',
        'final_decision',
        'applications',
    ];

    protected $attributes = [
        'title' => '',
        'description' => '',
        'meta' => [],
        'table' => [],
        'fields' => [],
        'request' => [],
        'rules' => [],
        'default_decision' => '',
        'final_decision' => '',
    ];

    public function __construct(array $attributes = [])
    {
        $this->attributes = array_merge($this->attributes, ['applications' => []]);
        parent::__construct($attributes);
    }

    protected $hidden = ['applications'];

    protected $dateFormat = \DateTime::ISO8601;

    protected $dates = ['created_at', 'updated_at'];

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
            'application' => $this->application,
            'title' => $this->title,
            'description' => $this->description,
            'final_decision' => $this->final_decision,
            'request' => $this->request,
            self::CREATED_AT => $this->getAttribute(self::CREATED_AT)->toIso8601String(),
            self::UPDATED_AT => $this->getAttribute(self::UPDATED_AT)->toIso8601String(),
            'rules' => $this->rules()->get()->map(function (Rule $rule) {
                return [
                    'title' => $rule->title,
                    'description' => $rule->description,
                    'decision' => $rule->decision,
                ];
            })->toArray(),
        ];
    }

    public function toArray()
    {
        # Cause property table have MongoID object
        $data = parent::toArray();
        $data['table'] = $this->getTableArray();

        return $data;
    }

    public function getTableArray()
    {
        $data = $this->getAttribute('table');
        $data['_id'] = strval($data['_id']);
        $data['variant']['_id'] = strval($data['variant']['_id']);

        return $data;
    }
}
