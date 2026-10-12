<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Models\Scopes\CentralAppScope;
use App\Support\Terbilang;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

#[ScopedBy(CentralAppScope::class)]
class Invoice extends Model
{
    use BelongsToTenant, HasFactory;

    private const ROMAN_MONTHS = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];

    protected $fillable = [
        'invoice_no',
        'invoice_date',
        'invoice_type',
        'confirm_order_id',
        'gross_amount',
        'deduction_amount',
        'amount',
        'discount_percentage',
        'discount_amount',
        'taxable_amount',
        'ppn_percentage',
        'ppn_amount',
        'total_amount',
        'status',
        'created_by',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'invoice_type' => InvoiceType::class,
        'status' => InvoiceStatus::class,
        'gross_amount' => 'decimal:2',
        'deduction_amount' => 'decimal:2',
        'amount' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'taxable_amount' => 'decimal:2',
        'ppn_percentage' => 'decimal:2',
        'ppn_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $invoice): void {
            $invoice->status ??= InvoiceStatus::UNPAID;

            if (empty($invoice->invoice_no)) {
                $invoice->invoice_no = static::nextInvoiceNo();
            }
        });
    }

    /**
     * Next sequential invoice number, e.g. "0001/CMS-MKT/INV/X/2026".
     */
    public static function nextInvoiceNo(): string
    {
        $lastNo = (string) static::query()->orderByDesc('id')->value('invoice_no');
        $last = (int) strtok($lastNo, '/');
        $sequence = str_pad((string) ($last + 1), 4, '0', STR_PAD_LEFT);
        $month = self::ROMAN_MONTHS[now()->month - 1];

        return "{$sequence}/CMS-MKT/INV/{$month}/".now()->year;
    }

    /**
     * Creates the Down Payment invoice automatically generated alongside a new
     * Confirm Order, from its down_payment_amount. Mirrors the Down Payment
     * branch of InvoiceController::computeAmounts() — no scope of work,
     * deduction, discount, or PPN for this invoice type.
     */
    public static function createDownPayment(ConfirmOrder $confirmOrder, float $amount, int $createdBy): self
    {
        $amount = round($amount, 2);

        return static::create([
            'invoice_date' => now()->toDateString(),
            'invoice_type' => InvoiceType::DOWN_PAYMENT->value,
            'confirm_order_id' => $confirmOrder->id,
            'gross_amount' => null,
            'deduction_amount' => 0,
            'amount' => $amount,
            'discount_percentage' => null,
            'discount_amount' => 0,
            'taxable_amount' => $amount,
            'ppn_percentage' => null,
            'ppn_amount' => 0,
            'total_amount' => $amount,
            'created_by' => $createdBy,
        ]);
    }

    public function confirmOrder(): BelongsTo
    {
        return $this->belongsTo(ConfirmOrder::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function scopeForProject($query, int $projectId)
    {
        return $query->whereHas('confirmOrder', fn ($q) => $q->where('project_id', $projectId));
    }

    /**
     * The Project this invoice bills against, via its Confirm Order.
     */
    public function getProjectAttribute(): ?Project
    {
        return $this->confirmOrder?->project;
    }

    /**
     * The exact Quotation revision tied to this invoice's Confirm Order
     * (what the Scope of Work/fees are actually sourced from).
     */
    public function getQuotationAttribute(): ?Quotation
    {
        return $this->confirmOrder?->quotation;
    }

    public function getClientAttribute(): ?Client
    {
        return $this->confirmOrder?->quotation?->client;
    }

    /**
     * The latest revision of this invoice's quotation (same quote_no family),
     * which may differ from the exact revision the Confirm Order was built on.
     */
    public function getLatestQuotationAttribute(): ?Quotation
    {
        $quoteNo = $this->quotation?->quote_no;

        if (! $quoteNo) {
            return null;
        }

        return Quotation::where('quote_no', $quoteNo)->orderByDesc('revision')->first();
    }

    /**
     * The invoice's grand total (amount + PPN), spelled out in Indonesian words.
     */
    public function getTerbilangAttribute(): string
    {
        return Terbilang::rupiah((float) $this->total_amount);
    }
}
