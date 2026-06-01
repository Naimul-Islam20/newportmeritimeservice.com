<?php

namespace App\Http\Requests\Admin;

use App\Models\CareerPage;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCareerPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        $page = $this->route('career_page');

        return $page instanceof CareerPage
            && $this->user()?->can('update', $page) === true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'hero_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'hero_background_file' => ['nullable', 'image', 'max:5120'],
            'remove_hero_background' => ['sometimes', 'boolean'],
            'eyebrow' => ['nullable', 'string', 'max:255'],
            'section_title' => ['nullable', 'string', 'max:255'],
            'intro_paragraphs' => ['nullable', 'array'],
            'intro_paragraphs.*' => ['nullable', 'string', 'max:5000'],
            'application_title' => ['nullable', 'string', 'max:255'],
            'application_lead' => ['nullable', 'string', 'max:1000'],
            'qualifications' => ['nullable', 'array'],
            'qualifications.*' => ['nullable', 'string', 'max:500'],
            'application_note' => ['nullable', 'string', 'max:2000'],
            'hr_email' => ['nullable', 'email', 'max:255'],
            'mail_button_label' => ['nullable', 'string', 'max:255'],
            'kariyer_url' => ['nullable', 'string', 'max:500'],
            'kariyer_button_label' => ['nullable', 'string', 'max:255'],
            'linkedin_url' => ['nullable', 'string', 'max:500'],
            'linkedin_button_label' => ['nullable', 'string', 'max:255'],
            'aside_image_file' => ['nullable', 'image', 'max:5120'],
            'aside_image_alt' => ['nullable', 'string', 'max:255'],
            'remove_aside_image' => ['sometimes', 'boolean'],
            'team_button_label' => ['nullable', 'string', 'max:255'],
            'team_button_url' => ['nullable', 'string', 'max:500'],
            'offers_eyebrow' => ['nullable', 'string', 'max:255'],
            'offers_title' => ['nullable', 'string', 'max:255'],
            'offers_card_title' => ['nullable', 'string', 'max:255'],
            'offers_paragraphs' => ['nullable', 'array'],
            'offers_paragraphs.*' => ['nullable', 'string', 'max:5000'],
            'bottom_cta_label' => ['nullable', 'string', 'max:255'],
            'bottom_cta_url' => ['nullable', 'string', 'max:500'],
        ];
    }
}
