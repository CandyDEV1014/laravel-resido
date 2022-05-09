<?php

namespace Botble\RealEstate\Http\Requests;

use Botble\RealEstate\Enums\ModerationStatusEnum;
use Botble\RealEstate\Enums\PropertyStatusEnum;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\RealEstate\Repositories\Interfaces\DetailInterface;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class PropertyAddRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
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
            'name.required'  => trans('plugins/real-estate::property.messages.request.name_required'),
            'content.required' => trans('plugins/real-estate::property.messages.request.content_required'),
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
