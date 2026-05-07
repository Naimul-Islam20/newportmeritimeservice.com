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
            'label' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string', 'max:2048'],
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
