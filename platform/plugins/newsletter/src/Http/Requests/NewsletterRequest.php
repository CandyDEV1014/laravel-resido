<?php

namespace Botble\Newsletter\Http\Requests;

use Botble\Newsletter\Enums\NewsletterStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class NewsletterRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'email'  => 'required|email|unique:newsletters',
            'status' => Rule::in(NewsletterStatusEnum::values()),
        ];

        if (is_plugin_active('captcha') && setting('enable_captcha')) {
            $rules += ['g-recaptcha-response' => 'required|captcha'];
        }

        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'g-recaptcha-response.required' => __('Captcha Verification Failed!'),
            'g-recaptcha-response.captcha'  => __('Captcha Verification Failed!'),
        ];
    }
}
