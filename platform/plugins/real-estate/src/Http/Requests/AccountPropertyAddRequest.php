<?php

namespace Botble\RealEstate\Http\Requests;

use Botble\RealEstate\Enums\PropertyStatusEnum;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\RealEstate\Repositories\Interfaces\DetailInterface;
use Botble\RealEstate\Http\Requests\PropertyRequest as BaseRequest;
use Illuminate\Validation\Rule;

class AccountPropertyAddRequest extends BaseRequest
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

        if ($this->request->get('details')) {
            foreach($this->request->get('details') as $key => $val)
            {
                $detail = app(DetailInterface::class)->getFirstBy(['id' => $key]);
                if ($detail->is_required) {
                    $rules['details.' . $key . '.value'] = 'required';
                }
                
            }
        }

        return $rules;
    }

    public function messages()
    {
        $message = [
            'name.required'  => trans('plugins/real-estate::account-property.messages.request.name_required'),
            'content.required' => trans('plugins/real-estate::account-property.messages.request.content_required'),
        ];

        if ($this->request->get('details')) {
            foreach($this->request->get('details') as $key => $val)
            {
                $detail = app(DetailInterface::class)->getFirstBy(['id' => $key]);
                if ($detail->is_required) {
                    $message['details.' . $key . '.value.required'] = $detail->name . ' is required';
                }
                
            }
        }

        return $message;
    }
}
