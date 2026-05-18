<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;

final class ImageUploadRules
{
    /**
     * @return list<string>
     */
    public static function rules(bool $required = false): array
    {
        $rules = ['image', 'mimes:jpeg,jpg,png,webp,gif', 'max:'.self::maxKilobytes()];
        array_unshift($rules, $required ? 'required' : 'nullable');

        return $rules;
    }

    public static function maxKilobytes(): int
    {
        $appMax = (int) config('uploads.max_image_kb', 5120);
        $phpMax = self::phpUploadMaxKilobytes();

        return $phpMax > 0 ? min($appMax, $phpMax) : $appMax;
    }

    public static function maxMegabytesLabel(): string
    {
        $mb = self::maxKilobytes() / 1024;

        return $mb >= 1 ? (string) round($mb, 1) : (string) round($mb, 2);
    }

    public static function invalidUploadMessage(UploadedFile $file): string
    {
        $maxLabel = self::maxMegabytesLabel();
        $phpIni = ini_get('upload_max_filesize') ?: '2M';

        return match ($file->getError()) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => "Image is too large. This server allows up to {$phpIni} per file (your screenshot may be bigger). Compress or resize it, or raise PHP upload_max_filesize.",
            UPLOAD_ERR_PARTIAL => 'Upload was interrupted. Please try again.',
            UPLOAD_ERR_NO_FILE => 'No image file was received.',
            default => "Image could not be uploaded. Use JPEG, PNG, WebP, or GIF under {$maxLabel} MB.",
        };
    }

    private static function phpUploadMaxKilobytes(): int
    {
        return (int) floor(self::parseIniSize(ini_get('upload_max_filesize') ?: '2M') / 1024);
    }

    private static function parseIniSize(string $value): int
    {
        $value = trim($value);
        if ($value === '') {
            return 2 * 1024 * 1024;
        }

        $unit = strtolower(substr($value, -1));
        $number = (float) $value;

        return (int) match ($unit) {
            'g' => $number * 1024 * 1024 * 1024,
            'm' => $number * 1024 * 1024,
            'k' => $number * 1024,
            default => $number,
        };
    }
}
