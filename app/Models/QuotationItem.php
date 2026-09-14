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
class QuotationItem extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'quotation_id',
        'master_item_id',
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

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }

    public function masterItem(): BelongsTo
    {
        return $this->belongsTo(MasterItem::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    public function scopeForQuotation($query, int $quotationId)
    {
        return $query->where('quotation_id', $quotationId);
    }

    /**
     * Total for this node: qty x unit_price for leaves, or the sum of its
     * children's totals for group/header nodes (requires children to be loaded).
     */
    public function getTotalAttribute(): float
    {
        if ($this->relationLoaded('children') && $this->children->isNotEmpty()) {
            return (float) $this->children->sum(fn (self $child) => $child->total);
        }

        return (float) ($this->qty ?? 0) * (float) $this->unit_price;
    }

    /**
     * Build the full item tree for a quotation, assigning a transient outline
     * `code` (A, A.1, A.1.1, ...) to each node based on its position among siblings.
     */
    public static function treeForQuotation(int $quotationId): Collection
    {
        $items = static::query()
            ->forQuotation($quotationId)
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
