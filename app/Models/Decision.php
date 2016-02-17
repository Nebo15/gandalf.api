<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 12.02.16
 * Time: 17:39
 */

namespace App\Models;

/**
 * Class Decision
 * @package App\Models
 * @property string $title
 * @property string $description
 * @property string $default_decision
 * @property Rule[] $rules
 * @property Field[] $fields
 * @method static Decision findById($id)
 * @method Decision save(array $options = [])
 * @method static \Illuminate\Pagination\LengthAwarePaginator paginate($perPage = 15, $columns = ['*'], $pageName = 'page', $page = null)
 */
class Decision extends Base
{
    protected $visible = ['_id', 'title', 'description', 'fields', 'rules', 'default_decision'];

    protected $fillable = ['title', 'description', 'default_decision', 'rules', 'fields'];

    protected $perPage = 20;

    public function rules()
    {
        return $this->embedsMany('App\Models\Rule');
    }

    public function fields()
    {
        return $this->embedsMany('App\Models\Field');
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
