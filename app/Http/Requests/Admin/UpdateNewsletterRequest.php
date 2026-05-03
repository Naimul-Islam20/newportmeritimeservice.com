<?php

namespace App\Http\Requests\Admin;

use App\Models\Newsletter;
use Illuminate\Foundation\Http\FormRequest;

class UpdateNewsletterRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Newsletter|null $newsletter */
        $newsletter = $this->route('newsletter');

        return $newsletter && ($this->user()?->can('update', $newsletter) ?? false);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:newsletter_categories,id'],
            'image' => ['nullable', 'image', 'max:4096'],
            'description' => ['required', 'string'],
        ];
    }
}
