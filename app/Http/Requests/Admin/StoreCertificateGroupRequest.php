<?php

namespace App\Http\Requests\Admin;

use App\Models\CertificateGroup;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCertificateGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', CertificateGroup::class) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', Rule::unique('certificate_groups', 'slug')],
            'intro' => ['nullable', 'string', 'max:2000'],
            'layout' => ['required', Rule::in([CertificateGroup::LAYOUT_GRID, CertificateGroup::LAYOUT_STACK])],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'show_divider_before' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
