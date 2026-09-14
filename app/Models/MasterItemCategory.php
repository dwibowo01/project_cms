<?php

namespace App\Models;

use App\Models\Scopes\CentralAppScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

#[ScopedBy(CentralAppScope::class)]
class MasterItemCategory extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'name',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(MasterItem::class, 'category_id');
    }
}
