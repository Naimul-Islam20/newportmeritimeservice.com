<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $name = trim((string) $this->input('name', ''));
        $surname = trim((string) $this->input('surname', ''));
        $company = trim((string) $this->input('company', ''));

        $this->merge([
            'full_name' => trim($name.' '.$surname),
            'subject' => $company !== '' ? $company : 'Contact enquiry',
            'phone' => trim((string) $this->input('phone', '')),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'surname' => ['required', 'string', 'max:120'],
            'company' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'message' => ['required', 'string', 'max:5000'],
            'full_name' => ['required', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
        ];
    }
}
