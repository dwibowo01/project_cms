<?php

namespace App\Enums;

enum InvoiceType: string
{
    case DOWN_PAYMENT = 'Down Payment';
    case PROGRESS_PAYMENT = 'Progress Payment';
    case FINAL_PAYMENT = 'Final Payment';
    case PROFORMA = 'Proforma';

    /**
     * Down Payment invoices skip the Scope of Work/progress table and PPN
     * entirely (per spec); every other type includes both.
     */
    public function requiresScopeOfWork(): bool
    {
        return $this !== self::DOWN_PAYMENT;
    }
}
