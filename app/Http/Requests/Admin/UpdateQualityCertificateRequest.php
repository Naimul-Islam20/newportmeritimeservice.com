<?php

namespace App\Http\Requests\Admin;

use App\Models\QualityCertificate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateQualityCertificateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $cert = $this->route('quality_certificate');

        return $cert instanceof QualityCertificate
            && ($this->user()?->can('update', $cert) ?? false);
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
            'pdf' => ['nullable', 'file', 'mimes:pdf', 'max:15360'],
        ];
    }
}
