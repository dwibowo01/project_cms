<?php

namespace App\Models;

use App\Models\Scopes\CentralAppScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

#[ScopedBy(CentralAppScope::class)]
class ProjectBudgetItem extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'project_id',
        'repair_list_item_id',
        'parent_id',
        'name',
        'qty',
        'unit',
        'unit_price',
        'sort_order',
    ];

    protected $casts = [
        'qty' => 'integer',
        'unit_price' => 'decimal:2',
        'sort_order' => 'integer',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function repairListItem(): BelongsTo
    {
        return $this->belongsTo(RepairListItem::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    public function scopeForProject($query, int $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    /**
     * Total for this node: qty x unit_price for leaves, or the sum of its
     * children's totals for group/header nodes (requires children to be loaded).
     * Mirrors QuotationItem::getTotalAttribute() exactly, per spec.
     */
    public function getTotalAttribute(): float
    {
        if ($this->relationLoaded('children') && $this->children->isNotEmpty()) {
            return (float) $this->children->sum(fn (self $child) => $child->total);
        }

        return (float) ($this->qty ?? 0) * (float) $this->unit_price;
    }

    /**
     * The originating Quotation item's unit price (via the linked Repair List
     * item's own quotation_item_id), or null when this budget item has no such
     * reference (e.g. added directly in the Project Budget, not cloned).
     */
    public function getReferenceUnitPriceAttribute(): ?float
    {
        $referencePrice = $this->repairListItem?->quotationItem?->unit_price;

        return $referencePrice !== null ? (float) $referencePrice : null;
    }

    /**
     * This item's unit price expressed as a percentage of the reference
     * Quotation unit price, or null when there's no reference to compare against.
     */
    public function getPercentageAttribute(): ?float
    {
        $reference = $this->reference_unit_price;

        if ($reference === null || $reference <= 0) {
            return null;
        }

        return round(((float) $this->unit_price / $reference) * 100, 2);
    }

    /**
     * Build the full item tree for a project, assigning a transient outline
     * `code` (A, A.1, A.1.1, ...) to each node based on its position among siblings.
     */
    public static function treeForProject(int $projectId): Collection
    {
        $items = static::query()
            ->forProject($projectId)
            ->with('repairListItem.quotationItem')
            ->orderBy('sort_order')
            ->get();

        $byParent = $items->groupBy('parent_id');

        $build = function ($parentId, int $depth) use (&$build, $byParent): Collection {
            return ($byParent->get($parentId) ?? collect())
                ->values()
                ->map(function (self $item, int $index) use (&$build, $depth) {
                    $item->setAttribute('depth', $depth);
                    $item->setAttribute('position', $index + 1);
                    $item->setRelation('children', $build($item->id, $depth + 1));

                    return $item;
                });
        };

        $roots = $build(null, 0);

        self::assignCodes($roots, null);

        return $roots;
    }

    /**
     * Clones a project's Repair List tree into its Project Budget, defaulting
     * each item's unit rate from the originating Quotation item (via the
     * Repair List item's own quotation_item_id link), if any.
     */
    public static function cloneFromRepairList(int $projectId): void
    {
        self::cloneNodes(RepairListItem::treeForProject($projectId), $projectId, null);
    }

    private static function cloneNodes(Collection $nodes, int $projectId, ?int $parentId): void
    {
        foreach ($nodes as $index => $node) {
            $item = static::create([
                'project_id' => $projectId,
                'repair_list_item_id' => $node->id,
                'parent_id' => $parentId,
                'name' => $node->name,
                'qty' => $node->qty,
                'unit' => $node->unit,
                'unit_price' => $node->quotationItem?->unit_price ?? 0,
                'sort_order' => $index + 1,
            ]);

            self::cloneNodes($node->children, $projectId, $item->id);
        }
    }

    private static function assignCodes(Collection $nodes, ?string $parentCode): void
    {
        foreach ($nodes as $index => $node) {
            $code = $parentCode === null
                ? chr(65 + $index)
                : $parentCode.'.'.($index + 1);

            $node->setAttribute('code', $code);

            self::assignCodes($node->children, $code);
        }
    }
}
