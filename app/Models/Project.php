<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use App\Enums\ProjectTeamRole;
use App\Enums\ProjectType;
use App\Models\Scopes\CentralAppScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

#[ScopedBy(CentralAppScope::class)]
class Project extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'project_no',
        'project_name',
        'client_id',
        'description',
        'docking_start_date',
        'docking_end_date',
        'status',
        'project_type',
    ];

    protected $casts = [
        'docking_start_date' => 'date',
        'docking_end_date' => 'date',
        'status' => ProjectStatus::class,
        'project_type' => ProjectType::class,
    ];

    protected static function booted(): void
    {
        static::creating(function (self $project): void {
            $project->status ??= ProjectStatus::ACTIVE;
            $project->project_type ??= ProjectType::forTenant(tenant('id'));

            if (empty($project->project_no)) {
                $project->project_no = static::nextProjectNo($project->project_type);
            }
        });
    }

    /**
     * Next sequential project number for the given type within the current
     * year, e.g. "2026-DC-0001".
     */
    public static function nextProjectNo(ProjectType $type): string
    {
        $prefix = now()->year.'-'.$type->code().'-';

        $lastProjectNo = (string) static::query()
            ->where('project_no', 'like', "{$prefix}%")
            ->orderByDesc('id')
            ->value('project_no');

        $lastSequence = $lastProjectNo ? (int) substr($lastProjectNo, strlen($prefix)) : 0;
        $sequence = str_pad((string) ($lastSequence + 1), 4, '0', STR_PAD_LEFT);

        return "{$prefix}{$sequence}";
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function confirmOrders(): HasMany
    {
        return $this->hasMany(ConfirmOrder::class);
    }

    public function teamMembers(): HasMany
    {
        return $this->hasMany(ProjectTeam::class);
    }

    /**
     * The single team member holding the "Project Manager Docking" role, if assigned.
     */
    public function manager(): HasOne
    {
        return $this->hasOne(ProjectTeam::class)->where('role', ProjectTeamRole::PROJECT_MANAGER_DOCKING->value);
    }

    /**
     * Every team member holding the "Member Docking" role.
     */
    public function members(): HasMany
    {
        return $this->hasMany(ProjectTeam::class)->where('role', ProjectTeamRole::MEMBER_DOCKING->value);
    }

    public function repairListItems(): HasMany
    {
        return $this->hasMany(RepairListItem::class);
    }

    public function projectBudgetItems(): HasMany
    {
        return $this->hasMany(ProjectBudgetItem::class);
    }

    /**
     * Users eligible to be assigned as PIC on this project's Repair List items:
     * the whole project team — the "Project Manager Docking" plus every
     * "Member Docking".
     */
    public function repairListPicOptions(): Collection
    {
        return $this->teamMembers()->with('user')->get()->pluck('user')->filter()->values();
    }

    /**
     * Every Invoice billed against this project through its Confirm Orders,
     * newest invoice date first.
     */
    public function getInvoicesAttribute(): Collection
    {
        return Invoice::query()->forProject($this->id)->latest('invoice_date')->get();
    }

    /**
     * Every Quotation tied to this project through its Confirm Orders,
     * including all other revisions of each (via Quotation::revisions()).
     * Sorted by quote_no, newest revision first within each quote_no.
     */
    public function getQuotationsAttribute(): Collection
    {
        return $this->confirmOrders
            ->pluck('quotation')
            ->filter()
            ->unique('quote_no')
            ->flatMap(fn (Quotation $quotation) => $quotation->revisions)
            ->sortBy([
                ['quote_no', 'asc'],
                ['revision', 'desc'],
            ])
            ->values();
    }
}
