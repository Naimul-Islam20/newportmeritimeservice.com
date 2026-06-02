<?php

namespace App\Http\Requests\Admin;

use App\Models\WhereWeAreLocation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWhereWeAreLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', WhereWeAreLocation::class) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'slug' => ['nullable', 'string', 'max:120', Rule::unique('where_we_are_locations', 'slug')],
            'hero_title' => ['required', 'string', 'max:255'],
            'region_label' => ['nullable', 'string', 'max:120'],
            'sidebar_label' => ['nullable', 'string', 'max:255'],
            'sidebar_extras' => ['nullable', 'array'],
            'sidebar_extras.*.label' => ['nullable', 'string', 'max:255'],
            'sidebar_extras.*.url' => ['nullable', 'string', 'max:500'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'hero_background_file' => ['nullable', 'image', 'max:5120'],
            'eyebrow' => ['nullable', 'string', 'max:255'],
            'office_title' => ['nullable', 'string', 'max:255'],
            'body_paragraphs' => ['nullable', 'array'],
            'body_paragraphs.*' => ['nullable', 'string', 'max:5000'],
            'map_embed' => ['nullable', 'string', 'max:10000'],
            'map_query' => ['nullable', 'string', 'max:500'],
            'brochure_label' => ['nullable', 'string', 'max:255'],
            'brochure_lead' => ['nullable', 'string', 'max:500'],
            'brochure_url' => ['nullable', 'string', 'max:500'],
            'body_link_label' => ['nullable', 'string', 'max:255'],
            'body_link_url' => ['nullable', 'string', 'max:500'],
            'certificate_group_slug' => ['nullable', 'string', 'max:120'],
            'membership_group_slug' => ['nullable', 'string', 'max:120'],
            'brochure_file_upload' => ['nullable', 'file', 'max:10240'],
            'show_quality_block' => ['sometimes', 'boolean'],
            'quality_block_title' => ['nullable', 'string', 'max:255'],
            'quality_block_lead' => ['nullable', 'string', 'max:1000'],
            'contact_cta_label' => ['nullable', 'string', 'max:255'],
            'contact_cta_url' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'show_quality_block' => $this->boolean('show_quality_block'),
        ]);
    }
}
