<?php

namespace App\Models;

use App\Models\Scopes\CentralAppScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

#[ScopedBy(CentralAppScope::class)]
class Quotation extends Model
{
    use BelongsToTenant, HasFactory;

    public const SURVEY_TYPES = ['AS', 'IS', 'SS'];

    private const ROMAN_MONTHS = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];

    protected $fillable = [
        'quote_no',
        'revision',
        'client_id',
        'client_contact_id',
        'ship_id',
        'master_item_category_id',
        'source_quotation_id',
        'created_by',
        'docking_year',
        'survey_type',
        'quotation_date',
    ];

    protected $casts = [
        'revision' => 'integer',
        'quotation_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $quotation): void {
            if (empty($quotation->quote_no)) {
                $quotation->quote_no = static::nextQuoteNo();
            }
        });
    }

    /**
     * Next sequential quote number, e.g. "0001/CMS-MKT/DOC/IX/2026".
     */
    public static function nextQuoteNo(): string
    {
        $lastQuoteNo = (string) static::query()->orderByDesc('id')->value('quote_no');
        $last = (int) strtok($lastQuoteNo, '/');
        $sequence = str_pad((string) ($last + 1), 4, '0', STR_PAD_LEFT);
        $month = self::ROMAN_MONTHS[now()->month - 1];

        return "{$sequence}/CMS-MKT/DOC/{$month}/".now()->year;
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function clientContact(): BelongsTo
    {
        return $this->belongsTo(ClientContact::class);
    }

    public function ship(): BelongsTo
    {
        return $this->belongsTo(Ship::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(MasterItemCategory::class, 'master_item_category_id');
    }

    public function sourceQuotation(): BelongsTo
    {
        return $this->belongsTo(self::class, 'source_quotation_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuotationItem::class);
    }

    /**
     * All rows sharing this quote_no (every revision), oldest first.
     */
    public function revisions(): HasMany
    {
        return $this->hasMany(self::class, 'quote_no', 'quote_no')->orderBy('revision');
    }

    public function isLatestRevision(): bool
    {
        return (int) static::where('quote_no', $this->quote_no)->max('revision') === $this->revision;
    }

    /**
     * Grand total across all items. Header/group rows carry qty=null and
     * unit_price=0, so summing qty*unit_price over every row (not just leaves)
     * already yields the correct total without needing to walk the tree.
     *
     * Uses the plain query builder (not Eloquent) so the raw "total" column
     * alias isn't shadowed by QuotationItem's own `total` accessor.
     */
    public function getGrandTotalAttribute(): float
    {
        return (float) DB::table('quotation_items')
            ->where('quotation_id', $this->id)
            ->selectRaw('COALESCE(SUM(qty * unit_price), 0) as total')
            ->value('total');
    }
}
