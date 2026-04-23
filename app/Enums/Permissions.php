<?php

namespace App\Enums;

enum Permissions: string
{
    case CREATE_TEAM_USER_BY_TEAM = 'create team user by team';
    case VIEW_TEAM_USER_BY_TEAM = 'view team user by team';
    case UPDATE_TEAM_USER_BY_TEAM = 'update team user by team';
    case DELETE_TEAM_USER_BY_TEAM = 'delete team user by team';

    case CREATE_ROLE_BY_TEAM = 'create role by team';
    case VIEW_ROLE_BY_TEAM = 'view role by team';
    case UPDATE_ROLE_BY_TEAM = 'update role by team';
    case DELETE_ROLE_BY_TEAM = 'delete role by team';

    case CREATE_PROJECT_BY_TEAM = 'create project by team';
    case VIEW_PROJECT_BY_TEAM = 'view project by team';
    case UPDATE_PROJECT_BY_TEAM = 'update project by team';
    case DELETE_PROJECT_BY_TEAM = 'delete project by team';

    case CREATE_GROUP_BY_TEAM = 'create group by team';
    case VIEW_GROUP_BY_TEAM = 'view group by team';
    case UPDATE_GROUP_BY_TEAM = 'update group by team';
    case DELETE_GROUP_BY_TEAM = 'delete group by team';

    case CREATE_GROUP_USER_BY_TEAM = 'create group user by team';
    case VIEW_GROUP_USER_BY_TEAM = 'view group user by team';
    case UPDATE_GROUP_USER_BY_TEAM = 'update group user by team';
    case DELETE_GROUP_USER_BY_TEAM = 'delete group user by team';

    case CREATE_TASK_BY_PROJECT = 'create task by project';
    case VIEW_TASK_BY_PROJECT = 'view task by project';
    case UPDATE_TASK_BY_PROJECT = 'update task by project';
    case DELETE_TASK_BY_PROJECT = 'delete task by project';

    case CREATE_QUOTATION = 'create quotation';
    case VIEW_QUOTATION = 'view quotation';
    case UPDATE_QUOTATION = 'update quotation';
    case DELETE_QUOTATION = 'delete quotation';

    case CREATE_CONFIRM_ORDER = 'create confirm order';
    case VIEW_CONFIRM_ORDER = 'view confirm order';
    case UPDATE_CONFIRM_ORDER = 'update confirm order';
    case DELETE_CONFIRM_ORDER = 'delete confirm order';

    case CREATE_LAUNCH_ORDER = 'create launch order';
    case VIEW_LAUNCH_ORDER = 'view launch order';
    case UPDATE_LAUNCH_ORDER = 'update launch order';
    case DELETE_LAUNCH_ORDER = 'delete launch order';

    case CREATE_INVOICE = 'create invoice';
    case VIEW_INVOICE = 'view invoice';
    case UPDATE_INVOICE = 'update invoice';
    case DELETE_INVOICE = 'delete invoice';

    case CREATE_REPAIR_LIST = 'create repair list';
    case VIEW_REPAIR_LIST = 'view repair list';
    case UPDATE_REPAIR_LIST = 'update repair list';
    case DELETE_REPAIR_LIST = 'delete repair list';

    public static function byRole(Roles $role): array
    {
        return match ($role) {
            Roles::ADMIN_DOCKING => [
                self::CREATE_TEAM_USER_BY_TEAM,
                self::VIEW_TEAM_USER_BY_TEAM,
                self::UPDATE_TEAM_USER_BY_TEAM,
                self::DELETE_TEAM_USER_BY_TEAM,

                self::CREATE_ROLE_BY_TEAM,
                self::VIEW_ROLE_BY_TEAM,
                self::UPDATE_ROLE_BY_TEAM,
                self::DELETE_ROLE_BY_TEAM,

                self::CREATE_PROJECT_BY_TEAM,
                self::VIEW_PROJECT_BY_TEAM,
                self::UPDATE_PROJECT_BY_TEAM,
                self::DELETE_PROJECT_BY_TEAM,

                self::CREATE_GROUP_BY_TEAM,
                self::VIEW_GROUP_BY_TEAM,
                self::UPDATE_GROUP_BY_TEAM,
                self::DELETE_GROUP_BY_TEAM,

                self::CREATE_GROUP_USER_BY_TEAM,
                self::VIEW_GROUP_USER_BY_TEAM,
                self::UPDATE_GROUP_USER_BY_TEAM,
                self::DELETE_GROUP_USER_BY_TEAM,

                self::CREATE_TASK_BY_PROJECT,
                self::VIEW_TASK_BY_PROJECT,
                self::UPDATE_TASK_BY_PROJECT,
                self::DELETE_TASK_BY_PROJECT,

                self::CREATE_QUOTATION,
                self::VIEW_QUOTATION,
                self::UPDATE_QUOTATION,
                self::DELETE_QUOTATION,

                self::CREATE_CONFIRM_ORDER,
                self::VIEW_CONFIRM_ORDER,
                self::UPDATE_CONFIRM_ORDER,
                self::DELETE_CONFIRM_ORDER,

                self::CREATE_LAUNCH_ORDER,
                self::VIEW_LAUNCH_ORDER,
                self::UPDATE_LAUNCH_ORDER,
                self::DELETE_LAUNCH_ORDER,

                self::CREATE_INVOICE,
                self::VIEW_INVOICE,
                self::UPDATE_INVOICE,
                self::DELETE_INVOICE,

                self::CREATE_REPAIR_LIST,
                self::VIEW_REPAIR_LIST,
                self::UPDATE_REPAIR_LIST,
                self::DELETE_REPAIR_LIST,
            ],
            Roles::PROJECT_MANAGER_DOCKING => [
                self::CREATE_TEAM_USER_BY_TEAM,
                self::VIEW_TEAM_USER_BY_TEAM,
                self::UPDATE_TEAM_USER_BY_TEAM,
                self::DELETE_TEAM_USER_BY_TEAM,

                self::CREATE_PROJECT_BY_TEAM,
                self::VIEW_PROJECT_BY_TEAM,
                self::UPDATE_PROJECT_BY_TEAM,
                self::DELETE_PROJECT_BY_TEAM,

                self::CREATE_GROUP_BY_TEAM,
                self::VIEW_GROUP_BY_TEAM,
                self::UPDATE_GROUP_BY_TEAM,
                self::DELETE_GROUP_BY_TEAM,

                self::CREATE_GROUP_USER_BY_TEAM,
                self::VIEW_GROUP_USER_BY_TEAM,
                self::UPDATE_GROUP_USER_BY_TEAM,
                self::DELETE_GROUP_USER_BY_TEAM,

                self::CREATE_TASK_BY_PROJECT,
                self::VIEW_TASK_BY_PROJECT,
                self::UPDATE_TASK_BY_PROJECT,
                self::DELETE_TASK_BY_PROJECT,

                self::CREATE_REPAIR_LIST,
                self::VIEW_REPAIR_LIST,
                self::UPDATE_REPAIR_LIST,
                self::DELETE_REPAIR_LIST,
            ],
            Roles::MARKETING_DOCKING => [
                self::CREATE_QUOTATION,
                self::VIEW_QUOTATION,
                self::UPDATE_QUOTATION,
                self::DELETE_QUOTATION,

                self::CREATE_CONFIRM_ORDER,
                self::VIEW_CONFIRM_ORDER,
                self::UPDATE_CONFIRM_ORDER,
                self::DELETE_CONFIRM_ORDER,

                self::CREATE_LAUNCH_ORDER,
                self::VIEW_LAUNCH_ORDER,
                self::UPDATE_LAUNCH_ORDER,
                self::DELETE_LAUNCH_ORDER,

                self::CREATE_INVOICE,
                self::VIEW_INVOICE,
                self::UPDATE_INVOICE,
                self::DELETE_INVOICE,
            ],
            Roles::MEMBER_DOCKING => [
                self::VIEW_TEAM_USER_BY_TEAM,
                self::VIEW_ROLE_BY_TEAM,
                self::VIEW_PROJECT_BY_TEAM,
                self::VIEW_GROUP_BY_TEAM,

                self::CREATE_GROUP_BY_TEAM,
                self::VIEW_GROUP_BY_TEAM,
                self::CREATE_TASK_BY_PROJECT,
                self::VIEW_TASK_BY_PROJECT,

                self::VIEW_QUOTATION,
                self::VIEW_CONFIRM_ORDER,
                self::VIEW_LAUNCH_ORDER,
                self::VIEW_INVOICE,
                self::VIEW_REPAIR_LIST,
            ],
            Roles::ADMIN_NEW_BUILDING => [
                self::CREATE_TEAM_USER_BY_TEAM,
                self::VIEW_TEAM_USER_BY_TEAM,
                self::UPDATE_TEAM_USER_BY_TEAM,
                self::DELETE_TEAM_USER_BY_TEAM,

                self::CREATE_ROLE_BY_TEAM,
                self::VIEW_ROLE_BY_TEAM,
                self::UPDATE_ROLE_BY_TEAM,
                self::DELETE_ROLE_BY_TEAM,

                self::CREATE_PROJECT_BY_TEAM,
                self::VIEW_PROJECT_BY_TEAM,
                self::UPDATE_PROJECT_BY_TEAM,
                self::DELETE_PROJECT_BY_TEAM,

                self::CREATE_GROUP_BY_TEAM,
                self::VIEW_GROUP_BY_TEAM,
                self::UPDATE_GROUP_BY_TEAM,
                self::DELETE_GROUP_BY_TEAM,

                self::CREATE_GROUP_USER_BY_TEAM,
                self::VIEW_GROUP_USER_BY_TEAM,
                self::UPDATE_GROUP_USER_BY_TEAM,
                self::DELETE_GROUP_USER_BY_TEAM,

                self::CREATE_TASK_BY_PROJECT,
                self::VIEW_TASK_BY_PROJECT,
                self::UPDATE_TASK_BY_PROJECT,
                self::DELETE_TASK_BY_PROJECT,

                self::CREATE_QUOTATION,
                self::VIEW_QUOTATION,
                self::UPDATE_QUOTATION,
                self::DELETE_QUOTATION,

                self::CREATE_CONFIRM_ORDER,
                self::VIEW_CONFIRM_ORDER,
                self::UPDATE_CONFIRM_ORDER,
                self::DELETE_CONFIRM_ORDER,

                self::CREATE_LAUNCH_ORDER,
                self::VIEW_LAUNCH_ORDER,
                self::UPDATE_LAUNCH_ORDER,
                self::DELETE_LAUNCH_ORDER,

                self::CREATE_INVOICE,
                self::VIEW_INVOICE,
                self::UPDATE_INVOICE,
                self::DELETE_INVOICE,

                self::CREATE_REPAIR_LIST,
                self::VIEW_REPAIR_LIST,
                self::UPDATE_REPAIR_LIST,
                self::DELETE_REPAIR_LIST,
            ],
            Roles::PROJECT_MANAGER_NEW_BUILDING => [
                self::CREATE_TEAM_USER_BY_TEAM,
                self::VIEW_TEAM_USER_BY_TEAM,
                self::UPDATE_TEAM_USER_BY_TEAM,
                self::DELETE_TEAM_USER_BY_TEAM,

                self::CREATE_PROJECT_BY_TEAM,
                self::VIEW_PROJECT_BY_TEAM,
                self::UPDATE_PROJECT_BY_TEAM,
                self::DELETE_PROJECT_BY_TEAM,

                self::CREATE_GROUP_BY_TEAM,
                self::VIEW_GROUP_BY_TEAM,
                self::UPDATE_GROUP_BY_TEAM,
                self::DELETE_GROUP_BY_TEAM,

                self::CREATE_GROUP_USER_BY_TEAM,
                self::VIEW_GROUP_USER_BY_TEAM,
                self::UPDATE_GROUP_USER_BY_TEAM,
                self::DELETE_GROUP_USER_BY_TEAM,

                self::CREATE_TASK_BY_PROJECT,
                self::VIEW_TASK_BY_PROJECT,
                self::UPDATE_TASK_BY_PROJECT,
                self::DELETE_TASK_BY_PROJECT,

                self::CREATE_REPAIR_LIST,
                self::VIEW_REPAIR_LIST,
                self::UPDATE_REPAIR_LIST,
                self::DELETE_REPAIR_LIST,
            ],
            Roles::MEMBER_NEW_BUILDING => [
                self::VIEW_TEAM_USER_BY_TEAM,
                self::VIEW_ROLE_BY_TEAM,
                self::VIEW_PROJECT_BY_TEAM,
                self::VIEW_GROUP_BY_TEAM,

                self::CREATE_GROUP_BY_TEAM,
                self::VIEW_GROUP_BY_TEAM,
                self::CREATE_TASK_BY_PROJECT,
                self::VIEW_TASK_BY_PROJECT,

                self::VIEW_QUOTATION,
                self::VIEW_CONFIRM_ORDER,
                self::VIEW_LAUNCH_ORDER,
                self::VIEW_INVOICE,
                self::VIEW_REPAIR_LIST,
            ],
            Roles::ADMIN_SITE => [
                self::CREATE_TEAM_USER_BY_TEAM,
                self::VIEW_TEAM_USER_BY_TEAM,
                self::UPDATE_TEAM_USER_BY_TEAM,
                self::DELETE_TEAM_USER_BY_TEAM,

                self::CREATE_ROLE_BY_TEAM,
                self::VIEW_ROLE_BY_TEAM,
                self::UPDATE_ROLE_BY_TEAM,
                self::DELETE_ROLE_BY_TEAM,

                self::CREATE_PROJECT_BY_TEAM,
                self::VIEW_PROJECT_BY_TEAM,
                self::UPDATE_PROJECT_BY_TEAM,
                self::DELETE_PROJECT_BY_TEAM,

                self::CREATE_GROUP_BY_TEAM,
                self::VIEW_GROUP_BY_TEAM,
                self::UPDATE_GROUP_BY_TEAM,
                self::DELETE_GROUP_BY_TEAM,

                self::CREATE_GROUP_USER_BY_TEAM,
                self::VIEW_GROUP_USER_BY_TEAM,
                self::UPDATE_GROUP_USER_BY_TEAM,
                self::DELETE_GROUP_USER_BY_TEAM,

                self::CREATE_TASK_BY_PROJECT,
                self::VIEW_TASK_BY_PROJECT,
                self::UPDATE_TASK_BY_PROJECT,
                self::DELETE_TASK_BY_PROJECT,
            ],
             Roles::PROJECT_MANAGER_SITE => [
                self::CREATE_TEAM_USER_BY_TEAM,
                self::VIEW_TEAM_USER_BY_TEAM,
                self::UPDATE_TEAM_USER_BY_TEAM,
                self::DELETE_TEAM_USER_BY_TEAM,

                self::CREATE_PROJECT_BY_TEAM,
                self::VIEW_PROJECT_BY_TEAM,
                self::UPDATE_PROJECT_BY_TEAM,
                self::DELETE_PROJECT_BY_TEAM,

                self::CREATE_GROUP_BY_TEAM,
                self::VIEW_GROUP_BY_TEAM,
                self::UPDATE_GROUP_BY_TEAM,
                self::DELETE_GROUP_BY_TEAM,

                self::CREATE_GROUP_USER_BY_TEAM,
                self::VIEW_GROUP_USER_BY_TEAM,
                self::UPDATE_GROUP_USER_BY_TEAM,
                self::DELETE_GROUP_USER_BY_TEAM,

                self::CREATE_TASK_BY_PROJECT,
                self::VIEW_TASK_BY_PROJECT,
                self::UPDATE_TASK_BY_PROJECT,
                self::DELETE_TASK_BY_PROJECT,
            ],
            Roles::MEMBER_SITE => [
                self::VIEW_TEAM_USER_BY_TEAM,
                self::VIEW_ROLE_BY_TEAM,
                self::VIEW_PROJECT_BY_TEAM,
                self::VIEW_GROUP_BY_TEAM,

                self::CREATE_GROUP_BY_TEAM,
                self::VIEW_GROUP_BY_TEAM,
                self::CREATE_TASK_BY_PROJECT,
                self::VIEW_TASK_BY_PROJECT,
            ],
            default => [],
        };
    }
}
