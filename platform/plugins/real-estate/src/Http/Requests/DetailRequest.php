<?php

namespace Botble\RealEstate\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\RealEstate\Enums\DetailTypeEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class DetailRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'   => 'required|max:120',
            'title'  => 'required|max:120',
            'alt'    => 'required|max:60',
            'icon'   => 'max:60',
            'type'   => Rule::in(DetailTypeEnum::values()),
            'order'  => 'numeric|required|min:0',
            'status' => Rule::in(BaseStatusEnum::values()),
            'categories'  => 'required',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'name.required'  => trans('plugins/real-estate::detail.messages.request.name_required'),
            'title.required' => trans('plugins/real-estate::detail.messages.request.title_required'),
            'type.required'  => trans('plugins/real-estate::detail.messages.request.type_required'),
            'order.required' => trans('plugins/real-estate::detail.messages.request.order_required'),
        ];
    }
}
