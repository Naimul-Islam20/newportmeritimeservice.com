<?php

namespace App\Http\Requests\Admin;

use App\Models\QualityCertificate;
use Illuminate\Foundation\Http\FormRequest;

class StoreQualityCertificateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', QualityCertificate::class) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:5120'],
            'pdf' => ['required', 'file', 'mimes:pdf', 'max:15360'],
        ];
    }
}
