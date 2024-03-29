<?php

namespace Botble\RealEstate\Http\Requests;

use Botble\RealEstate\Enums\PropertyStatusEnum;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\RealEstate\Repositories\Interfaces\DetailInterface;
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
        $rules = [
            'name'            => 'required',
            'description'     => 'max:350',
            'content'         => 'required',
            'price'           => 'numeric|min:0|nullable',
            'status'          => Rule::in(PropertyStatusEnum::values()),
        ];

        $details = app(DetailInterface::class)->allBy(['re_details.status' => BaseStatusEnum::PUBLISHED]);

        if (count($details)) {
            foreach($details as $key => $detail)
            {
                if ($detail->is_required) {
                    $rules['details.' . $detail->id . '.value'] = 'required';
                }
            }
        }

        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        $message = [
            'name.required'  => trans('plugins/real-estate::account-property.messages.request.name_required'),
            'content.required' => trans('plugins/real-estate::account-property.messages.request.content_required'),
        ];
        $details = app(DetailInterface::class)->allBy(['re_details.status' => BaseStatusEnum::PUBLISHED]);

        if (count($details)) {
            foreach($details as $key => $detail)
            {
                if ($detail->is_required) {
                    $message['details.' . $detail->id . '.value.required'] = $detail->name . ' is required';
                }
            }
        }

        return $message;
    }
}
