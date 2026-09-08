<?php

namespace App\Models;

use App\Models\Scopes\CentralAppScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

#[ScopedBy(CentralAppScope::class)]
class ClientContact extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'client_id',
        'name',
        'position',
        'phone_number',
        'email',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
