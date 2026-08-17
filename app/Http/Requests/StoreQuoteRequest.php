<?php

namespace App\Http\Requests;

use App\Rules\Recaptcha;
use Illuminate\Foundation\Http\FormRequest;

class StoreQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'company' => ['nullable', 'string', 'max:255'],
            'vessel_or_reference' => ['nullable', 'string', 'max:255'],
            'request_details' => ['required', 'string', 'max:5000'],
            'timeline' => ['nullable', 'string', 'max:255'],
            'g-recaptcha-response' => ['required', new Recaptcha],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'g-recaptcha-response.required' => 'Please complete the captcha.',
        ];
    }
}
