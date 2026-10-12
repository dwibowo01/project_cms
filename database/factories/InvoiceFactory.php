<?php

namespace Database\Factories;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Models\ConfirmOrder;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        $amount = $this->faker->numberBetween(10_000_000, 500_000_000);

        return [
            'invoice_date' => now()->toDateString(),
            'invoice_type' => InvoiceType::DOWN_PAYMENT,
            'confirm_order_id' => ConfirmOrder::factory(),
            'gross_amount' => null,
            'deduction_amount' => 0,
            'amount' => $amount,
            'discount_percentage' => null,
            'discount_amount' => 0,
            'taxable_amount' => $amount,
            'ppn_percentage' => null,
            'ppn_amount' => 0,
            'total_amount' => $amount,
            'status' => InvoiceStatus::UNPAID,
        ];
    }
}
