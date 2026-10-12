<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case PLANNING = 'Planning';
    case ACTIVE = 'Active';
    case ON_HOLD = 'On Hold';
    case CANCEL = 'Cancel';
    case COMPLETE = 'Complete';
}
