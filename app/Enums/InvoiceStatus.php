<?php

namespace App\Enums;

enum InvoiceStatus: string
{
    case UNPAID = 'Unpaid';
    case PAID = 'Paid';
}
