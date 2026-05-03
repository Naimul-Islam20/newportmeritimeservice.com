<?php

namespace App\Http\Requests\Admin;

use App\Models\NewsletterCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateNewsletterCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var NewsletterCategory|null $newsletterCategory */
        $newsletterCategory = $this->route('newsletter_category');

        return $newsletterCategory && ($this->user()?->can('update', $newsletterCategory) ?? false);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var NewsletterCategory $newsletterCategory */
        $newsletterCategory = $this->route('newsletter_category');

        return [
            'name' => ['required', 'string', 'max:120', Rule::unique('newsletter_categories', 'name')->ignore($newsletterCategory->id)],
        ];
    }
}
