<?php
/*
 * This code was generated automatically by Nebo15/REST
 */

namespace App\Models;

use App\Exceptions\VariantNotFound;
use Nebo15\LumenApplicationable\Contracts\Applicationable;
use Nebo15\LumenApplicationable\Traits\ApplicationableTrait;
use Nebo15\REST\Traits\ListableTrait;
use Nebo15\REST\Interfaces\ListableInterface;

/**
 * Class Table
 * @package App\Models
 * @property string $title
 * @property string $description
 * @property string $matching_type
 * @property Variant[] $variants
 * @property Field[] $fields
 * @method static Decision findById($id)
 * @method static Decision create(array $attributes = [])
 * @method Decision save(array $options = [])
 * @method static \Illuminate\Pagination\LengthAwarePaginator paginate($perPage = 15, $columns = ['*'], $pageName = 'page', $page = null)
 */
class Table extends Base implements ListableInterface, Applicationable
{
    use ListableTrait, ApplicationableTrait;

    protected $listable = ['_id', 'title', 'description', 'matching_type', 'default_decision'];

    protected $attributes = [
        'title' => '',
        'description' => '',
        'matching_type' => 'first',
        'variants_probability' => '',
    ];

    protected $visible = [
        '_id',
        'title',
        'description',
        'matching_type',
        'variants_probability',
        'fields',
        'variants',
    ];

    protected $fillable = [
        'title',
        'description',
        'matching_type',
        'variants_probability',
    ];

    protected $perPage = 20;

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
            'fields' => $this->fields,
            'variants' => $this->variants,
        ];
    }

    public function fields()
    {
        return $this->embedsMany('App\Models\Field');
    }

    public function variants()
    {
        return $this->embedsMany('App\Models\Variant');
    }

    public function setFields($fields)
    {
        $this->fields()->delete();
        foreach ($fields as $field) {
            $fieldModel = new Field($field);
            if (isset($field['preset'])) {
                $fieldModel->preset()->associate(new Preset($field['preset']));
            }
            $this->fields()->associate($fieldModel);
        }

        return $this;
    }

    public function setVariants($variants)
    {
        $this->variants()->delete();
        foreach ($variants as $variant) {
            $this->variants()->associate((new Variant($variant))->setRules($variant['rules']));
        }

        return $this;
    }

    /**
     * @param null $variantId
     * @return Variant
     * @throws VariantNotFound
     */
    public function getVariantForCheck($variantId = null)
    {
        $collection = $this->variants()->get();
        if ($variantId) {
            $variant = $collection->find($variantId);
        } elseif ($collection->count() > 0) {
            $variant = $collection->random();
        } else {
            $variant = $collection->first();
        }

        if (!$variant) {
            throw new VariantNotFound;
        }

        return $variant;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getFieldsKeys()
    {
        return $this->fields()->get()->map(function (Field $field) {
            return $field->key;
        });
    }
}
