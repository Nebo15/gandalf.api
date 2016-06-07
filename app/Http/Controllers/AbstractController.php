<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 07.06.16
 * Time: 17:11
 */

namespace App\Http\Controllers;

use App\Services\ConditionsTypes;

abstract class AbstractController extends \Nebo15\REST\AbstractController
{
    protected function getTableRules(ConditionsTypes $conditionsTypes)
    {
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
            'fields.*.preset.condition' => 'required_with:fields.*.preset|in:' . $conditionsTypes->getConditionsRules(),
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
            'variants.*.rules.*.conditions.*.condition' => 'required|in:' . $conditionsTypes->getConditionsRules(),
            'variants.*.rules.*.conditions.*.value' => 'required|conditionType',
        ];
    }
}
