<?php

namespace App\Models;

use App\Models\Scopes\CentralAppScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

#[ScopedBy(CentralAppScope::class)]
class InvoiceItem extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'invoice_id',
        'repair_list_item_id',
        'name',
        'fee',
        'progress_percentage',
        'discount_percentage',
        'amount',
        'sort_order',
    ];

    protected $casts = [
        'fee' => 'decimal:2',
        'progress_percentage' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'amount' => 'decimal:2',
        'sort_order' => 'integer',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function repairListItem(): BelongsTo
    {
        return $this->belongsTo(RepairListItem::class);
    }
}
