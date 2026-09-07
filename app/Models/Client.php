<?php

namespace App\Models;

use App\Models\Scopes\CentralAppScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

#[ScopedBy(CentralAppScope::class)]
class Client extends Model
{
    use BelongsToTenant, HasFactory;

    public const COMPANY_TYPES = ['Perseorangan', 'CV', 'PT', 'Lainnya'];

    protected $fillable = [
        'client_no',
        'client_id',
        'client_company_type',
        'client_name',
        'client_phone_country_code',
        'client_phone_number',
        'client_email',
        'company_address_line_1',
        'company_address_line_2',
        'country',
        'state',
        'city',
        'postal_code',
        'website',
        'notes',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $client): void {
            if (empty($client->client_no)) {
                $client->client_no = static::nextClientNo();
            }
        });
    }

    /**
     * Next sequential, 4-digit client number (e.g. 0001, 0002, ...).
     */
    public static function nextClientNo(): string
    {
        $last = (int) static::query()->orderByDesc('id')->value('client_no');

        return str_pad((string) ($last + 1), 4, '0', STR_PAD_LEFT);
    }
}
