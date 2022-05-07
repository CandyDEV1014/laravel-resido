<?php

namespace Botble\RealEstate\Http\Requests;

use Botble\RealEstate\Enums\PropertyStatusEnum;
use Botble\RealEstate\Http\Requests\PropertyRequest as BaseRequest;
use Illuminate\Validation\Rule;

class AccountPropertyRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'            => 'required',
            'description'     => 'max:350',
            'content'         => 'required',
            'price'           => 'numeric|min:0|nullable',
            'status'          => Rule::in(PropertyStatusEnum::values()),
        ];
    }
}
