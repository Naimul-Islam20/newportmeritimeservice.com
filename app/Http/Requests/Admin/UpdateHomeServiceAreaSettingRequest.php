<?php

namespace App\Http\Requests\Admin;

use App\Models\HomeServiceAreaSetting;
use Illuminate\Foundation\Http\FormRequest;

class UpdateHomeServiceAreaSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        if (! $user?->isAdmin()) {
            return false;
        }

        $setting = HomeServiceAreaSetting::query()->first();
        if ($setting instanceof HomeServiceAreaSetting) {
            return $user->can('update', $setting);
        }

        return $user->can('viewAny', HomeServiceAreaSetting::class);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'mini_title' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'highlight_title' => ['nullable', 'string', 'max:255'],
            'highlight_description' => ['nullable', 'string', 'max:5000'],
            'steps' => ['nullable', 'array', 'max:12'],
            'steps.*' => ['nullable', 'string', 'max:500'],
            'map_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:5120'],
            'branches_mini_title' => ['nullable', 'string', 'max:255'],
            'branches_title' => ['nullable', 'string', 'max:255'],
            'branches_view_all_label' => ['nullable', 'string', 'max:255'],
            'branches_view_all_url' => ['nullable', 'string', 'max:2048'],
            'branch_items' => ['nullable', 'array', 'max:12'],
            'branch_items.*.label' => ['nullable', 'string', 'max:255'],
            'branch_items.*.subtitle' => ['nullable', 'string', 'max:255'],
            'branch_items.*.url' => ['nullable', 'string', 'max:2048'],
            'branch_items.*.file' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:5120'],
            'branch_items.*.existing_path' => ['nullable', 'string', 'max:500'],
        ];
    }
}
