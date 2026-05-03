<?php

namespace App\Http\Requests\Admin;

use App\Models\NewsletterCategory;
use Illuminate\Foundation\Http\FormRequest;

class StoreNewsletterCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', NewsletterCategory::class) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120', 'unique:newsletter_categories,name'],
        ];
    }
}
