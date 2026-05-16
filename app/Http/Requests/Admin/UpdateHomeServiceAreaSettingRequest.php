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
        ];
    }
}
