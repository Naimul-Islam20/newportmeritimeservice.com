<?php

namespace App\Http\Requests\Admin;

use App\Models\Menu;
use Illuminate\Foundation\Http\FormRequest;

class UpdateShipSupplyLandingRequest extends FormRequest
{
    public function authorize(): bool
    {
        $menu = Menu::shipSupplyMenu();

        return $menu && ($this->user()?->can('update', $menu) ?? false);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,gif,svg', 'max:5120'],
            'remove_cover_image' => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'remove_cover_image' => $this->boolean('remove_cover_image'),
        ]);
    }
}
