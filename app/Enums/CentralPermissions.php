<?php

namespace App\Enums;

enum CentralPermissions: string
{
    case CREATE_TEAM = 'create team';
    case VIEW_TEAM = 'view team';
    case UPDATE_TEAM = 'update team';
    case DELETE_TEAM = 'delete team';

    case CREATE_TEAM_USER = 'create team user';
    case VIEW_TEAM_USER = 'view team user';
    case UPDATE_TEAM_USER = 'update team user';
    case DELETE_TEAM_USER = 'delete team user';

    case CREATE_ROLE = 'create role';
    case VIEW_ROLE = 'view role';
    case UPDATE_ROLE = 'update role';
    case DELETE_ROLE = 'delete role';
}
