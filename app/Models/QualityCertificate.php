<?php

namespace App\Models;

use App\Support\PublicUploadUrl;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class QualityCertificate extends Model
{
    protected $fillable = [
        'certificate_group_id',
        'title',
        'image_path',
        'pdf_path',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'certificate_group_id' => 'integer',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(CertificateGroup::class, 'certificate_group_id');
    }

    public function imagePublicUrl(): string
    {
        return PublicUploadUrl::fromPath($this->image_path);
    }

    public function pdfPublicUrl(): string
    {
        return PublicUploadUrl::fromPath($this->pdf_path);
    }

    public function hasViewablePdf(): bool
    {
        return $this->pdfPublicUrl() !== '';
    }

    /** Home carousel: always link to the certificates page (optional group anchor). */
    public function carouselHref(): string
    {
        $slug = $this->relationLoaded('group')
            ? $this->group?->slug
            : $this->group()->value('slug');

        if (is_string($slug) && $slug !== '') {
            return route('quality-certificates').'#'.$slug;
        }

        return route('quality-certificates');
    }

    /**
     * Active certificates for the home page carousel (group order, then certificate order).
     *
     * @return Collection<int, static>
     */
    public static function forHomeCarousel(): Collection
    {
        return self::query()
            ->where('quality_certificates.is_active', true)
            ->join('certificate_groups', 'certificate_groups.id', '=', 'quality_certificates.certificate_group_id')
            ->where('certificate_groups.is_active', true)
            ->orderBy('certificate_groups.sort_order')
            ->orderBy('quality_certificates.sort_order')
            ->orderBy('quality_certificates.id')
            ->select('quality_certificates.*')
            ->with('group')
            ->get();
    }

    public static function isManagedUploadPath(?string $path, string $prefix): bool
    {
        if (! is_string($path) || $path === '') {
            return false;
        }
        if (preg_match('#^https?://#i', $path)) {
            return false;
        }
        $p = ltrim(str_replace('\\', '/', $path), '/');

        return str_starts_with($p, rtrim($prefix, '/').'/');
    }

    public static function deleteManagedUpload(?string $path): void
    {
        if (! is_string($path) || $path === '') {
            return;
        }
        if (preg_match('#^https?://#i', $path)) {
            return;
        }
        $p = ltrim(str_replace('\\', '/', $path), '/');
        if (Storage::disk('public_site')->exists($p)) {
            Storage::disk('public_site')->delete($p);
        }
        if (Storage::disk('public')->exists($p)) {
            Storage::disk('public')->delete($p);
        }
    }
}
