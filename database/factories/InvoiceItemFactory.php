<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InvoiceItem>
 */
class InvoiceItemFactory extends Factory
{
    protected $model = InvoiceItem::class;

    public function definition(): array
    {
        $fee = $this->faker->numberBetween(1_000_000, 100_000_000);
        $progress = $this->faker->randomElement([25, 50, 75, 100]);

        return [
            'invoice_id' => Invoice::factory(),
            'name' => $this->faker->sentence(4),
            'fee' => $fee,
            'progress_percentage' => $progress,
            'discount_percentage' => 0,
            'amount' => round($fee * $progress / 100),
            'sort_order' => 1,
        ];
    }
}
