<?php
/*
 * This code was generated automatically by Nebo15/REST
 */

namespace App\Models;

use App\Exceptions\VariantNotFound;
use App\Services\ConditionsTypes;
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
 * @property string $variants_probability
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

    protected $listable = ['_id', 'title', 'description', 'matching_type'];

    protected $attributes = [
        'title' => '',
        'description' => '',
        'matching_type' => 'decision',
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
        $variant = null;
        $collection = $this->variants()->get();
        if ($variantId) {
            $variant = $collection->find($variantId);
        } else {
            switch ($this->variants_probability) {
                case 'first':
                    $variant = $collection->first();
                    break;
                case 'random':
                    $variant = $collection->count() > 1 ? $collection->random() : $collection->first();
                    break;
                case 'percent':
                    if ($collection->count() == 1) {
                        $variant = $collection->first();
                    } else {
                        $i = 0;
                        $percent = rand(1, 100);
                        /** @var Condition $item */
                        foreach ($collection->all() as $item) {
                            $i = $i + $item->probability;
                            if ($i >= $percent) {
                                $variant = $item;
                                break;
                            }
                        }
                    }
                    break;
                default:
                    $variant = $collection->first();
            }
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

    public function getValidationRules()
    {
        $condRules = (new ConditionsTypes)->getConditionsRules();
        return [
            'title' => 'sometimes|string',
            'description' => 'sometimes|string',
            'matching_type' => 'required|in:decision,scoring',
            'fields' => 'required|array',
            'fields.*._id' => 'sometimes|mongoId',
            'fields.*.title' => 'required|string',
            'fields.*.key' => 'required|string|not_in:variant_id',
            'fields.*.type' => 'required|in:numeric,boolean,string',
            'fields.*.source' => 'required|in:request',
            'fields.*.preset' => 'present|array',
            'fields.*.preset._id' => 'mongoId',
            'fields.*.preset.value' => 'required_with:fields.*.preset',
            'fields.*.preset.condition' => 'required_with:fields.*.preset|in:' . $condRules,
            'variants_probability' => 'sometimes|in:first,random',
            'variants' => 'required|array',
            'variants.*._id' => 'mongoId',
            'variants.*.default_decision' => 'required|ruleThanType',
            'variants.*.title' => 'sometimes|string|between:2,128',
            'variants.*.description' => 'sometimes|string|between:2,128',
            'variants.*.default_title' => 'sometimes|string|between:2,128',
            'variants.*.default_description' => 'sometimes|string|between:2,512',
            'variants.*.rules' => 'required|array',
            'variants.*.rules.*._id' => 'mongoId',
            'variants.*.rules.*.than' => 'required|ruleThanType',
            'variants.*.rules.*.description' => 'string|between:2,128',
            'variants.*.rules.*.conditions' => 'required|array|conditionsCount',
            'variants.*.rules.*.conditions.*._id' => 'mongoId',
            'variants.*.rules.*.conditions.*.field_key' => 'required|string',
            'variants.*.rules.*.conditions.*.condition' => 'required|in:' . $condRules,
            'variants.*.rules.*.conditions.*.value' => 'required|conditionType',
        ];
    }
}
