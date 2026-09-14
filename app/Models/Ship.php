<?php

namespace App\Models;

use App\Models\Scopes\CentralAppScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

#[ScopedBy(CentralAppScope::class)]
class Ship extends Model
{
    use BelongsToTenant, HasFactory;

    public const SHIP_TYPES = ['Barge', 'SPOB', 'SPB', 'Cargo', 'Tanker', 'Tug Boat', 'Others'];

    public const UNITS = ['meter', 'ft'];

    protected $fillable = [
        'ship_name',
        'ship_type',
        'client_id',
        'loa_value',
        'loa_unit',
        'lbp_value',
        'lbp_unit',
        'height_value',
        'height_unit',
        'width_value',
        'width_unit',
        'draught_value',
        'draught_unit',
        'gt',
        'nt',
        'power_me',
    ];

    protected $casts = [
        'loa_value' => 'decimal:2',
        'lbp_value' => 'decimal:2',
        'height_value' => 'decimal:2',
        'width_value' => 'decimal:2',
        'draught_value' => 'decimal:2',
        'gt' => 'decimal:2',
        'nt' => 'decimal:2',
        'power_me' => 'decimal:2',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
