<?php

namespace App\Http\Requests\Admin;

use App\Models\ServiceSidebarSetting;
use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceSidebarRequest extends FormRequest
{
    public function authorize(): bool
    {
        $setting = $this->route('service_sidebar_setting');

        return $setting instanceof ServiceSidebarSetting
            && $this->user()?->can('update', $setting) === true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'categories_title' => ['nullable', 'string', 'max:255'],
            'spare_parts_title' => ['nullable', 'string', 'max:255'],
            'spare_parts_text' => ['nullable', 'string', 'max:500'],
            'spare_parts_button_label' => ['nullable', 'string', 'max:255'],
            'brochures_title' => ['nullable', 'string', 'max:255'],
            'brochures_text' => ['nullable', 'string', 'max:500'],
            'brochure_label' => ['nullable', 'string', 'max:255'],
            'brochure_url' => ['nullable', 'string', 'max:500'],
            'quote_title' => ['nullable', 'string', 'max:255'],
            'nav_group_id' => ['nullable', 'array'],
            'nav_group_label' => ['nullable', 'array'],
            'nav_child_label' => ['nullable', 'array'],
            'nav_child_href' => ['nullable', 'array'],
            'nav_link_label' => ['nullable', 'array'],
            'nav_link_href' => ['nullable', 'array'],
        ];
    }
}
