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

            $fallbackAddress = $branch['address'] ?? '';
            $address = $location?->contactAddressForPublic($fallbackAddress) ?? $fallbackAddress;

            $phone = $defaultPhone;
            $email = $defaultEmail;

            $map = $location
                ? $location->contactMapEmbed($branch['label'], $address !== '' ? $address : null)
                : MapEmbed::resolve(null, $address !== '' ? $address : null, $branch['label']);

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
     * Contact page tabs and their linked Where We Are location slugs.
     *
     * @return list<array{id: string, label: string, location_slug: string, address: string}>
     */
    public static function branchDefinitions(): array
    {
        return [
            [
                'id' => 'chattogram',
                'label' => 'Chattogram',
                'location_slug' => 'chattogram-port',
                'address' => '1110/B, Hasna Tower (6th Floor), Agrabad C/A, Chittagong.',
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
                'id' => 'matarbari',
                'label' => 'Matarbari',
                'location_slug' => 'matarbari-port',
                'address' => 'Matarbari Port, Maheshkhali, Cox\'s Bazar, Bangladesh.',
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
