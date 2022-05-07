<?php

namespace Botble\RealEstate\Http\Requests;

use Botble\RealEstate\Enums\ModerationStatusEnum;
use Botble\RealEstate\Enums\PropertyStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class PropertyRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'              => 'required',
            'description'       => 'max:350',
            'content'           => 'required',
            'price'             => 'numeric|min:0|nullable',
            'latitude'          => ['max:20', 'nullable', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'longitude'         => [
                'max:20',
                'nullable',
                'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/',
            ],
            'status'            => Rule::in(PropertyStatusEnum::values()),
            'moderation_status' => Rule::in(ModerationStatusEnum::values()),
        ];
    }
}
