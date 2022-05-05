<?php

namespace Botble\Block\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class BlockRequest extends Request
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
            'alias'  => 'required|max:120',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
