<?php

namespace App\Http\Requests\Admin;

use App\Models\CertificatePage;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCertificatePageRequest extends FormRequest
{
    public function authorize(): bool
    {
        $page = CertificatePage::singleton();

        return $this->user()?->can('update', $page) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'hero_title' => ['required', 'string', 'max:255'],
            'page_intro' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'is_active' => ['sometimes', 'boolean'],
            'hero_background_file' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:5120'],
            'remove_hero_background' => ['sometimes', 'boolean'],
        ];
    }
}
