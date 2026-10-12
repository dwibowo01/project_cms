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
class RepairListItem extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'project_id',
        'quotation_item_id',
        'parent_id',
        'name',
        'qty',
        'unit',
        'pic_user_id',
        'sort_order',
    ];

    protected $casts = [
        'qty' => 'integer',
        'sort_order' => 'integer',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function quotationItem(): BelongsTo
    {
        return $this->belongsTo(QuotationItem::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    public function pic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pic_user_id');
    }

    public function scopeForProject($query, int $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    /**
     * This node's value priced at the ORIGINAL Quotation unit rate — sum of
     * children's totals if a parent/group, else qty × the linked QuotationItem's
     * unit_price if a leaf. Deliberately independent of Project Budget, whose
     * unit_price may have since been edited away from the Quotation.
     */
    public function getQuotationTotalAttribute(): float
    {
        if ($this->relationLoaded('children') && $this->children->isNotEmpty()) {
            return (float) $this->children->sum(fn (self $child) => $child->quotation_total);
        }

        return (float) $this->qty * (float) ($this->quotationItem?->unit_price ?? 0);
    }

    /**
     * Assigns this item's current PIC to every descendant, recursively.
     * Used whenever a parent/group node's PIC is set or changed.
     */
    public function cascadePicToChildren(): void
    {
        $children = static::query()->where('parent_id', $this->id)->get();

        foreach ($children as $child) {
            $child->update(['pic_user_id' => $this->pic_user_id]);
            $child->cascadePicToChildren();
        }
    }

    /**
     * Build the full item tree for a project, assigning a transient outline
     * `code` (A, A.1, A.1.1, ...) to each node based on its position among siblings.
     */
    public static function treeForProject(int $projectId): Collection
    {
        $items = static::query()
            ->forProject($projectId)
            ->with('quotationItem')
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
     * Clones a quotation's item tree (name/qty/unit only — no pricing) into a
     * project's Repair List, linking each clone back to its source QuotationItem.
     */
    public static function cloneFromQuotation(int $projectId, int $quotationId): void
    {
        self::cloneNodes(QuotationItem::treeForQuotation($quotationId), $projectId, null);
    }

    private static function cloneNodes(Collection $nodes, int $projectId, ?int $parentId): void
    {
        foreach ($nodes as $index => $node) {
            $item = static::create([
                'project_id' => $projectId,
                'quotation_item_id' => $node->id,
                'parent_id' => $parentId,
                'name' => $node->name,
                'qty' => $node->qty,
                'unit' => $node->unit,
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
