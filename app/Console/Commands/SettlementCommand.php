<?php

namespace App\Console\Commands;

use App\Models\PaymentOrder;
use App\Payments\Fondy;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SettlementCommand extends Command
{
    protected $signature = 'trattoria:settlement';

    protected $description = 'Разбирание счета';

    public function handle(): void
    {
        $payments = PaymentOrder::query()
            ->with([
                'order',
                'order.payment',
                'order.items'
            ])
            ->where('status', 1)
            ->where('settlement', 0)
            ->where('created_at', '<', Carbon::now()->subMinutes(180))
            ->get();

        foreach ($payments as $payment) {
            $fondy = new Fondy();
            $fondy->settlement($payment->order, $payment->payments);
        }
    }
}
