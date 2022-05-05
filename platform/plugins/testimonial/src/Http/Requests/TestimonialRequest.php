<?php

namespace Botble\Testimonial\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class TestimonialRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'    => 'required',
            'content' => 'required|max:1000',
            'status'  => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
