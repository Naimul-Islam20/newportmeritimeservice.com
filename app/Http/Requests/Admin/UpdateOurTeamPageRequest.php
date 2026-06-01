<?php

namespace App\Http\Requests\Admin;

use App\Models\OurTeamPage;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOurTeamPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        $page = $this->route('our_team_page');

        return $page instanceof OurTeamPage
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
            'breadcrumb_label' => ['nullable', 'string', 'max:255'],
            'page_title' => ['nullable', 'string', 'max:255'],
            'regional_label' => ['nullable', 'array'],
            'regional_label.*' => ['nullable', 'string', 'max:255'],
            'regional_url' => ['nullable', 'array'],
            'regional_url.*' => ['nullable', 'string', 'max:2048'],
            'category_label' => ['nullable', 'array'],
            'category_label.*' => ['nullable', 'string', 'max:255'],
            'category_url' => ['nullable', 'array'],
            'category_url.*' => ['nullable', 'string', 'max:2048'],
            'section_heading' => ['nullable', 'array'],
            'section_heading.*' => ['nullable', 'string', 'max:255'],
            'member_name' => ['nullable', 'array'],
            'member_role' => ['nullable', 'array'],
            'member_email' => ['nullable', 'array'],
            'member_phone' => ['nullable', 'array'],
            'member_photo_path' => ['nullable', 'array'],
            'member_photo' => ['nullable', 'array'],
            'member_photo.*' => ['nullable', 'image', 'max:5120'],
            'hero_background_file' => ['nullable', 'image', 'max:5120'],
            'remove_hero_background' => ['sometimes', 'boolean'],
        ];
    }
}
