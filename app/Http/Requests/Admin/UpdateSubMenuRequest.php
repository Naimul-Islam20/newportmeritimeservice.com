<?php

namespace App\Http\Requests\Admin;

use App\Models\SubMenu;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubMenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        $subMenu = $this->route('sub_menu') ?? $this->route('subMenu');

        return $subMenu instanceof SubMenu
            && ($this->user()?->can('update', $subMenu) ?? false);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'menu_id' => ['required', 'integer', Rule::exists('menus', 'id')],
            'parent_sub_menu_id' => [
                'nullable',
                'integer',
                Rule::exists('sub_menus', 'id')->where(function ($q) {
                    $q->where('menu_id', $this->input('menu_id'))
                        ->whereNull('parent_sub_menu_id')
                        ->where('id', '!=', $this->route('sub_menu')?->id);
                }),
            ],
            'label' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:2048'],
            'description' => ['nullable', 'string', 'max:5000'],
            'page_content' => ['nullable', 'string', 'max:65535'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,gif,svg', 'max:5120'],
            'icon_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,svg', 'max:2048'],
            'published_at' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
