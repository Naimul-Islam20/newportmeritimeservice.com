<?php

namespace App\Http\Requests\Admin;

use App\Models\AboutPage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateAboutPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        $aboutPage = $this->route('about_page');
        if (! $aboutPage instanceof AboutPage) {
            return false;
        }

        return $this->user()?->can('update', $aboutPage) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'hero_title' => ['nullable', 'string', 'max:255'],
            'trust_title' => ['nullable', 'string', 'max:2000'],
            'trust_description' => ['nullable', 'string', 'max:20000'],
            'stat1_value' => ['nullable', 'string', 'max:64'],
            'stat1_label' => ['nullable', 'string', 'max:255'],
            'stat2_value' => ['nullable', 'string', 'max:64'],
            'stat2_label' => ['nullable', 'string', 'max:255'],
            'stat3_value' => ['nullable', 'string', 'max:64'],
            'stat3_label' => ['nullable', 'string', 'max:255'],
            'mission_title' => ['nullable', 'string', 'max:255'],
            'mission_body' => ['nullable', 'string', 'max:20000'],
            'vision_title' => ['nullable', 'string', 'max:255'],
            'vision_body' => ['nullable', 'string', 'max:20000'],
            'cta_eyebrow' => ['nullable', 'string', 'max:255'],
            'cta_heading' => ['nullable', 'string', 'max:2000'],
            'cta_button_label' => ['nullable', 'string', 'max:255'],
            'cta_video_url' => ['nullable', 'string', 'max:2048'],
            'hero_background_file' => ['nullable', 'image', 'max:5120'],
            'trust_image_file' => ['nullable', 'image', 'max:5120'],
            'cta_background_file' => ['nullable', 'image', 'max:5120'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v): void {
            $url = $this->input('cta_video_url');
            if (! is_string($url) || trim($url) === '') {
                return;
            }
            $norm = ltrim(str_replace('\\', '/', trim($url)), '/');
            if (str_starts_with($norm, 'about-page/videos/')) {
                return;
            }
            if (AboutPage::videoModalPayload($url)['type'] === 'none') {
                $v->errors()->add('cta_video_url', 'Use a valid YouTube link (watch, youtu.be, or embed URL).');
            }
        });
    }
}
