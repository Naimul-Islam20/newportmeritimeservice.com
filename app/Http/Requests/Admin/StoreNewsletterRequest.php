<?php

namespace App\Http\Requests\Admin;

use App\Models\Newsletter;
use Illuminate\Foundation\Http\FormRequest;

class StoreNewsletterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Newsletter::class) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:newsletter_categories,id'],
            'image' => ['required', 'image', 'max:4096'],
            'description' => ['required', 'string'],
        ];
    }
}
