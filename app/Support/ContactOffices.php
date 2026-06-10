<?php

namespace App\Support;

use App\Models\SiteDetail;
use App\Models\WhereWeAreLocation;

final class ContactOffices
{
    /**
     * @return list<array{id: string, label: string, active: bool, phone: string, email: string, address: string, map: object}>
     */
    public static function forContactPage(?SiteDetail $siteDetails = null, ?string $activeLocationSlug = null): array
    {
        $siteDetails ??= SiteDetail::query()->first();

        $emails = self::stringList($siteDetails?->emails);
        $phones = self::stringList($siteDetails?->phones);
        $defaultEmail = $emails[0] ?? '';
        $defaultPhone = $phones[0] ?? '';
        $headAddress = trim((string) ($siteDetails?->location ?? ''));
        $headMapRaw = trim((string) ($siteDetails?->map ?? ''));

        $branches = self::branchDefinitions();
        $locations = WhereWeAreLocation::activeOrdered();
        $locationBySlug = collect($locations)->keyBy('slug');
        $activeLocationSlug = $activeLocationSlug !== null ? trim($activeLocationSlug) : null;

        $offices = [];

        foreach ($branches as $index => $branch) {
            $location = $locationBySlug->get($branch['location_slug']);
            $isActive = $activeLocationSlug !== null
                ? $branch['location_slug'] === $activeLocationSlug
                : $index === 0;

            $address = $headAddress;
            if ($index > 0 || $headAddress === '') {
                $address = $branch['address'];
            }

            $phone = $defaultPhone;
            $email = $defaultEmail;

            $map = $index === 0 && $headMapRaw !== ''
                ? MapEmbed::resolve($headMapRaw, null, $branch['label'])
                : MapEmbed::resolve(
                    $location?->map_embed,
                    $location?->map_query ?? $address,
                    $branch['label'],
                );

            $offices[] = [
                'id' => $branch['id'],
                'label' => $branch['label'],
                'active' => $isActive,
                'phone' => $phone,
                'email' => $email,
                'address' => $address,
                'map' => $map,
            ];
        }

        if ($activeLocationSlug !== null && ! collect($offices)->contains(fn (array $office): bool => $office['active'])) {
            $offices[0]['active'] = true;
        }

        return $offices;
    }

    /**
     * @return list<array{id: string, label: string, location_slug: string, address: string}>
     */
    private static function branchDefinitions(): array
    {
        return [
            [
                'id' => 'chattogram',
                'label' => 'Chattogram',
                'location_slug' => 'chattogram-port',
                'address' => 'Chattogram Port Authority, Bandar Area, Chattogram 4100, Bangladesh.',
            ],
            [
                'id' => 'mongla',
                'label' => 'Mongla',
                'location_slug' => 'mongla-port',
                'address' => 'Mongla Port Authority, Mongla, Bagerhat District, Khulna Division, Bangladesh.',
            ],
            [
                'id' => 'payra',
                'label' => 'Payra',
                'location_slug' => 'payra-port',
                'address' => 'Payra Port Authority, Itbaria, Kalapara Upazila, Patuakhali District, Bangladesh.',
            ],
            [
                'id' => 'coxs-bazar',
                'label' => "Cox's Bazar",
                'location_slug' => 'coxs-bazar-port',
                'address' => "Cox's Bazar Fishing Harbour, Kasturi Ghat Road, Cox's Bazar 4700, Bangladesh.",
            ],
        ];
    }

    /**
     * @return list<string>
     */
    private static function stringList(mixed $raw): array
    {
        if (! is_array($raw)) {
            return [];
        }

        return array_values(array_filter(array_map(
            fn ($v) => is_string($v) ? trim($v) : '',
            $raw,
        ), fn ($v) => $v !== ''));
    }
}
