<?php

namespace App\Http\Requests\Admin;

use App\Models\HeroSlide;
use App\Support\ImageUploadRules;
use Illuminate\Foundation\Http\FormRequest;

class UpdateHeroSlideRequest extends FormRequest
{
    public function authorize(): bool
    {
        $slide = $this->route('hero_slide');

        return $slide instanceof HeroSlide
            && ($this->user()?->can('update', $slide) ?? false);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:65535'],
            'button_label' => ['nullable', 'string', 'max:120'],
            'button_url' => ['nullable', 'string', 'max:2048'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'image' => ImageUploadRules::rules(),
        ];
    }
}
