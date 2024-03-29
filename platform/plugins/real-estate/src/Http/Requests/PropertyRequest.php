<?php

namespace Botble\RealEstate\Http\Requests;

use Botble\RealEstate\Enums\ModerationStatusEnum;
use Botble\RealEstate\Enums\PropertyStatusEnum;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\RealEstate\Repositories\Interfaces\DetailInterface;
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
        
        // $details = $this->detailRepository->allBy([], [], ['re_details.id', 're_details.name', 're_details.type', 're_details.order', 're_details.features']);
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
            'name.required'  => trans('plugins/real-estate::property.messages.request.name_required'),
            'content.required' => trans('plugins/real-estate::property.messages.request.content_required'),
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
