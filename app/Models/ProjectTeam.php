<?php

namespace App\Models;

use App\Enums\ProjectTeamRole;
use App\Models\Scopes\CentralAppScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

#[ScopedBy(CentralAppScope::class)]
class ProjectTeam extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'role',
    ];

    protected $casts = [
        'role' => ProjectTeamRole::class,
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
