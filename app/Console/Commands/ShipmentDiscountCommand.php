<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DiscountCalculator\Interfaces\DiscountCalculatorInterface;
use pp\Services\Interfaces\FileServiceInterface;

class ShipmentDiscountCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:discount-calculator {fileName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calcu;ate shipment prices and discounts';

    /** @var DiscountCalculatorInterface */
    private $discountCalculator;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(DiscountCalculatorInterface $discountCalculator) {
        parent::__construct();

        $this->discountCalculator = $discountCalculator;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $fileName = $this->argument('fileName');

        $this->comment('Calculating price and discount for the shipments orders');

        $shipmentDiscount = $this->discountCalculator->calculateDiscounts($fileName);

        foreach($shipmentDiscount->getOrders() as $order) {

            if (!$order['valid']) {
                $this->error(
                    sprintf(
                        '%s %s %s',
                        $order['date'],
                        $order['size'],
                        'ignored'
                    )
                );

                continue;
            }

            $this->info(
                sprintf(
                    '%s %s %s %01.2f %01.2f',
                    $order['date'],
                    $order['size'],
                    $order['provider'],
                    $order['price'],
                    $order['discount']
                )
            );

        }
        return 0;
    }
}
