<?php

namespace App\Http\Requests\Admin;

use App\Models\OurStoryPage;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOurStoryPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        $page = $this->route('our_story_page');

        return $page instanceof OurStoryPage
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
            'section_title' => ['nullable', 'string', 'max:255'],
            'intro_paragraphs' => ['nullable', 'array'],
            'intro_paragraphs.*' => ['nullable', 'string', 'max:5000'],
            'milestone_year' => ['nullable', 'array'],
            'milestone_year.*' => ['nullable', 'string', 'max:32'],
            'milestone_title' => ['nullable', 'array'],
            'milestone_title.*' => ['nullable', 'string', 'max:255'],
            'milestone_text' => ['nullable', 'array'],
            'milestone_text.*' => ['nullable', 'string', 'max:5000'],
            'milestone_image_path' => ['nullable', 'array'],
            'milestone_image_path.*' => ['nullable', 'string', 'max:500'],
            'milestone_image' => ['nullable', 'array'],
            'milestone_image.*' => ['nullable', 'image', 'max:5120'],
            'hero_background_file' => ['nullable', 'image', 'max:5120'],
            'remove_hero_background' => ['sometimes', 'boolean'],
        ];
    }
}
