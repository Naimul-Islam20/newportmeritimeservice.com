<?php

namespace App\Http\Requests\Admin;

use App\Models\ServicePage;
use Illuminate\Foundation\Http\FormRequest;

class UpdateServicePageRequest extends FormRequest
{
    public function authorize(): bool
    {
        $page = $this->route('service_page');

        return $page instanceof ServicePage
            && $this->user()?->can('update', $page) === true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'content_layout' => ['nullable', 'string', 'in:full,simple'],
            'hero_title' => ['nullable', 'string', 'max:255'],
            'breadcrumb_label' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'open_nav_group_id' => ['nullable', 'string', 'max:100'],
            'hero_background_file' => ['nullable', 'image', 'max:5120'],
            'remove_hero_background' => ['sometimes', 'boolean'],
            'eyebrow' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'gallery_image_path_0' => ['nullable', 'string', 'max:500'],
            'gallery_image_path_1' => ['nullable', 'string', 'max:500'],
            'gallery_image_0_file' => ['nullable', 'image', 'max:5120'],
            'gallery_image_1_file' => ['nullable', 'image', 'max:5120'],
            'remove_gallery_image_0' => ['sometimes', 'boolean'],
            'remove_gallery_image_1' => ['sometimes', 'boolean'],
            'lead_paragraph' => ['nullable', 'string', 'max:500'],
            'body_paragraphs' => ['nullable', 'array'],
            'body_paragraphs.*' => ['nullable', 'string', 'max:5000'],
            'highlight_paragraph' => ['nullable', 'string', 'max:5000'],
            'services_heading' => ['nullable', 'string', 'max:255'],
            'service_columns' => ['nullable', 'array'],
            'service_columns.*' => ['nullable', 'array'],
            'service_columns.*.*' => ['nullable', 'string', 'max:255'],
            'content_image_file' => ['nullable', 'image', 'max:5120'],
            'remove_content_image' => ['sometimes', 'boolean'],
            'card_icon_file' => ['nullable', 'image', 'max:5120'],
            'remove_card_icon' => ['sometimes', 'boolean'],
            'why_heading' => ['nullable', 'string', 'max:255'],
            'why_paragraphs' => ['nullable', 'array'],
            'why_paragraphs.*' => ['nullable', 'string', 'max:5000'],
            'why_card_title' => ['nullable', 'array'],
            'why_card_title.*' => ['nullable', 'string', 'max:255'],
            'why_card_icon' => ['nullable', 'array'],
            'why_card_icon.*' => ['nullable', 'string', 'max:50'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
