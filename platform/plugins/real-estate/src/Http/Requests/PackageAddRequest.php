<?php

namespace Botble\RealEstate\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class PackageAddRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'                   => 'required',
            'price'                  => 'numeric|required|min:0',
            'currency_id'            => 'required',
            'credits'                => 'numeric|required|min:0',
            'number_of_days'         => 'numeric|required|min:-1',
            'number_of_properties'   => 'numeric|required|min:-1',
            'number_of_aminities'    => 'numeric|required|min:-1',
            'number_of_nearestplace' => 'numeric|required|min:-1',
            'number_of_photo'        => 'numeric|required|min:-1',
            'status'                 => Rule::in(BaseStatusEnum::values()),
        ];

        if ($this->input('is_allow_featured') == 1) {
            $rules['number_of_featured'] = 'numeric|required|min:-1';
        }

        if ($this->input('is_allow_top') == 1) {
            $rules['number_of_top'] = 'numeric|required|min:-1';
        }

        if ($this->input('is_allow_urgent') == 1) {
            $rules['number_of_urgent'] = 'numeric|required|min:-1';
        }

        if ($this->input('is_promotion') == 1) {
            $rules['promotion_time'] = 'required';
            $rules['promotion_price'] = 'numeric|required|min:0';
        }
        return $rules;
    }

}

