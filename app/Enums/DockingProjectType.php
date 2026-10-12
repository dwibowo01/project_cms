<?php

namespace App\Enums;

// Describes the kind of docking job a Quotation covers (not to be confused
// with Project::$project_type, which distinguishes Docking/New Building/Site
// tenants). Drives the "PENAWARAN [TYPE] [Ship Name]" title shown on the
// Quotation, and carries through to the Project created from its Confirm Order.
enum DockingProjectType: string
{
    case DOCKING = 'DOCKING';
    case FLOATING = 'FLOATING';
    case ONSITE = 'ONSITE';
}
