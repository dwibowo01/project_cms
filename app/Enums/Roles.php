<?php

namespace App\Enums;

enum Roles: string
{
    case ADMIN_DOCKING = 'admin docking';
    case PROJECT_MANAGER_DOCKING = 'project manager docking';
    case MARKETING_DOCKING = 'marketing docking';
    case MEMBER_DOCKING = 'member docking';

    case ADMIN_NEW_BUILDING = 'admin new building';
    case PROJECT_MANAGER_NEW_BUILDING = 'project manager new building';
    case MEMBER_NEW_BUILDING = 'member new building';

    case ADMIN_SITE = 'admin site';
    case PROJECT_MANAGER_SITE = 'project manager site';
    case MEMBER_SITE = 'member site';

    public static function byTenant(string $tenantId): array
    {
        return match ($tenantId) {
            'docking' => [
                self::ADMIN_DOCKING,
                self::PROJECT_MANAGER_DOCKING,
                self::MARKETING_DOCKING,
                self::MEMBER_DOCKING,
            ],
            'new-building' => [
                self::ADMIN_NEW_BUILDING,
                self::PROJECT_MANAGER_NEW_BUILDING,
                self::MEMBER_NEW_BUILDING,
            ],
            'site' => [
                self::ADMIN_SITE,
                self::PROJECT_MANAGER_SITE,
                self::MEMBER_SITE,
            ],
            default => [],
        };
    }

    public static function adminForTenant(string $tenantId): ?self
    {
        return match ($tenantId) {
            'docking'      => self::ADMIN_DOCKING,
            'new-building' => self::ADMIN_NEW_BUILDING,
            'site'         => self::ADMIN_SITE,
            default        => null,
        };
    }
}
