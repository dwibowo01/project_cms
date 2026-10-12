<?php

namespace App\Models;

use App\Models\Scopes\CentralAppScope;
use App\Support\Terbilang;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

#[ScopedBy(CentralAppScope::class)]
class ConfirmOrder extends Model
{
    use BelongsToTenant, HasFactory;

    private const ROMAN_MONTHS = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];

    protected $fillable = [
        'confirm_order_no',
        'quotation_id',
        'project_id',
        'docking_start_date',
        'ppn',
        'down_payment_amount',
        'created_by',
    ];

    protected $casts = [
        'docking_start_date' => 'date',
        'ppn' => 'boolean',
        'down_payment_amount' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $confirmOrder): void {
            if (empty($confirmOrder->confirm_order_no)) {
                $confirmOrder->confirm_order_no = static::nextConfirmOrderNo();
            }
        });
    }

    /**
     * Next sequential confirm order number, e.g. "0001/CMS-MKT/CO/X/2026".
     */
    public static function nextConfirmOrderNo(): string
    {
        $lastNo = (string) static::query()->orderByDesc('id')->value('confirm_order_no');
        $last = (int) strtok($lastNo, '/');
        $sequence = str_pad((string) ($last + 1), 4, '0', STR_PAD_LEFT);
        $month = self::ROMAN_MONTHS[now()->month - 1];

        return "{$sequence}/CMS-MKT/CO/{$month}/".now()->year;
    }

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Scope of work: the quotation's parent (top-level) items, each already
     * carrying its own total (sum of its children) via QuotationItem::$total.
     */
    public function getScopeOfWorkAttribute(): Collection
    {
        return QuotationItem::treeForQuotation($this->quotation_id);
    }

    /**
     * Grand total across the scope of work, equivalent to the quotation's total.
     */
    public function getScopeOfWorkTotalAttribute(): float
    {
        return (float) $this->scope_of_work->sum(fn (QuotationItem $item) => $item->total);
    }

    /**
     * The down payment amount, spelled out in Indonesian words, lowercase and
     * unadorned — matching the in-sentence style of the Confirm Order PDF's
     * payment terms (e.g. "dua ratus juta rupiah"), unlike Invoice's Title Case.
     */
    public function getDownPaymentTerbilangAttribute(): string
    {
        return mb_strtolower(Terbilang::rupiah((float) $this->down_payment_amount));
    }
}
