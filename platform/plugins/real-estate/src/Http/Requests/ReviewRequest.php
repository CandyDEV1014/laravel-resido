<?php

namespace Botble\RealEstate\Http\Requests;

use Botble\Support\Http\Requests\Request;

class ReviewRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'reviewable_id' => 'required',
            'star'          => 'required|numeric|min:1|max:5',
            'comment'       => 'required|max:1000',
        ];
    }
}
