<?php

namespace App\Http\Requests\Admin;

use App\Models\SiteDetail;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSiteDetailRequest extends FormRequest
{
    public function authorize(): bool
    {
        $detail = $this->route('site_detail');
        if (! $detail instanceof SiteDetail) {
            return false;
        }

        return $this->user()?->can('update', $detail) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'location' => ['nullable', 'string', 'max:5000'],
            'map' => ['nullable', 'string', 'max:20000'],
            'emails' => ['nullable', 'array', 'max:25'],
            'emails.*' => ['nullable', 'string', 'email:rfc,dns', 'max:255'],
            'phones' => ['nullable', 'array', 'max:25'],
            'phones.*' => ['nullable', 'string', 'max:40'],
            'social' => ['nullable', 'array'],
            'social.facebook' => ['nullable', 'string', 'max:2048'],
            'social.linkedin' => ['nullable', 'string', 'max:2048'],
            'social.youtube' => ['nullable', 'string', 'max:2048'],
            'social.twitter' => ['nullable', 'string', 'max:2048'],
            'default_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:5120'],
        ];
    }
}
