<?php

namespace Botble\RealEstate\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class PackageRequest extends Request
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
            'number_of_featured'     => 'numeric|required|min:-1',
            'number_of_top'          => 'numeric|required|min:-1',
            'number_of_urgent'       => 'numeric|required|min:-1',
            'promotion_time'         => 'required',
            'promotion_price'        => 'numeric|required|min:0',
            'status'                 => Rule::in(BaseStatusEnum::values()),
        ];

        return $rules;
    }

}

