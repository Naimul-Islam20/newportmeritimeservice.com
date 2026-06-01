<?php

namespace App\Http\Requests\Admin;

use App\Models\CeoMessagePage;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCeoMessagePageRequest extends FormRequest
{
    public function authorize(): bool
    {
        $page = $this->route('ceo_message_page');

        return $page instanceof CeoMessagePage
            && ($this->user()?->can('update', $page) ?? false);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'hero_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'eyebrow' => ['nullable', 'string', 'max:255'],
            'salutation' => ['nullable', 'string', 'max:500'],
            'paragraphs' => ['nullable', 'array'],
            'paragraphs.*' => ['nullable', 'string', 'max:10000'],
            'signature_name' => ['nullable', 'string', 'max:255'],
            'signature_role' => ['nullable', 'string', 'max:255'],
            'linkedin_url' => ['nullable', 'string', 'max:2048'],
            'instagram_url' => ['nullable', 'string', 'max:2048'],
            'hero_background_file' => ['nullable', 'image', 'max:5120'],
            'portrait_file' => ['nullable', 'image', 'max:5120'],
            'remove_hero_background' => ['sometimes', 'boolean'],
            'remove_portrait' => ['sometimes', 'boolean'],
        ];
    }
}
