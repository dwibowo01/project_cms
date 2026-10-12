<?php

namespace App\Enums;

enum ProjectType: string
{
    case DOCKING = 'Docking';
    case NEW_BUILDING = 'New Building';
    case SITE = 'Site';

    /**
     * Short code used as the middle segment of a project number, e.g. "2026-DC-0001".
     */
    public function code(): string
    {
        return match ($this) {
            self::DOCKING => 'DC',
            self::NEW_BUILDING => 'NB',
            self::SITE => 'SITE',
        };
    }

    /**
     * Default project type for the given tenant id (docking, new-building, site).
     */
    public static function forTenant(?string $tenantId): self
    {
        return match ($tenantId) {
            'new-building' => self::NEW_BUILDING,
            'site' => self::SITE,
            default => self::DOCKING,
        };
    }
}
