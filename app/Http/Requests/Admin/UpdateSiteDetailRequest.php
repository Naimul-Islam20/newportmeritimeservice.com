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

    protected function prepareForValidation(): void
    {
        $hexKeys = [
            'theme_brand_navy',
            'theme_brand_navy_mid',
            'theme_brand_accent',
            'theme_brand_accent_hover',
            'theme_brand_topbar_muted',
            'theme_footer_overlay_base',
        ];
        $merge = [];
        foreach ($hexKeys as $key) {
            $v = $this->input($key);
            if ($v === '' || $v === null) {
                $merge[$key] = null;
            }
        }
        $op = $this->input('theme_footer_overlay_opacity');
        if ($op === '' || $op === null) {
            $merge['theme_footer_overlay_opacity'] = null;
        }
        $this->merge($merge);
        $this->merge([
            'reset_theme_colors' => $this->boolean('reset_theme_colors'),
        ]);
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
            'header_logo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,gif,svg', 'max:5120'],
            'footer_logo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,gif,svg', 'max:5120'],
            'reset_theme_colors' => ['sometimes', 'boolean'],
            'theme_brand_navy' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'theme_brand_navy_mid' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'theme_brand_accent' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'theme_brand_accent_hover' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'theme_brand_topbar_muted' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'theme_footer_overlay_base' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'theme_footer_overlay_opacity' => ['nullable', 'integer', 'min:0', 'max:100'],
        ];
    }
}
