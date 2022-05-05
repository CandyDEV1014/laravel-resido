<?php

namespace Botble\RealEstate\Http\Requests;

use Botble\Support\Http\Requests\Request;

class TypeRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch (request()->route()->getName()) {
            case 'property_type.create':
                return [
                    'name' => 'required',
                    'slug' => 'required|unique:re_property_types',
                ];
            default:
                return [
                    'name' => 'required',
                ];
        }
    }
}
