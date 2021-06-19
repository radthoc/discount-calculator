<?php

namespace Tests\Unit\Rules;

use Tests\TestCase;
use Illuminate\Support\Carbon;
use App\Entities\ShipmentDiscount;
use App\Services\DiscountCalculator\Rules\MediumShipmentRulePerOrder;

class MediumShipmentRulePerOrderTest extends TestCase
{
    /** @dataProvider shipmentValues */
    public function testMediumShipmentRule(
        string $date,
        string $size,
        string $provider,
        float $expectedPrice,
        float $expectedDiscount
    ) {
        $expectedResult = [];

        $shipmentDiscount = new ShipmentDiscount();
        
        $mediumShipmentRule = $this->app->make(MediumShipmentRulePerOrder::class);

        $shipmentDiscount->addOrder(
            $date,
            $size,
            $provider
        );

        $shipmentDiscount = $mediumShipmentRule->calculateShipmentDiscount($shipmentDiscount);

        $currentOrder = $shipmentDiscount->getCurrentOrder();

        $this->assertEquals($expectedPrice, $currentOrder['price']);
        $this->assertEquals($expectedDiscount, $currentOrder['discount']);
    }

    public function shipmentValues()
    {
        return [
            ['2015-02-09', 'M', 'LP', 4.90, 0],
            ['2015-02-01', 'S', 'MR', 0, 0],
            ['2015-02-02', 'S', 'MR', 0, 0],
            ['2015-02-03', 'L', 'LP', 0, 0],
            ['2015-02-08', 'M', 'MR', 3, 0],
            ['2015-02-09', 'M', 'LP', 4.90, 0],
            ['2015-02-10', 'L', 'LP', 0, 0],
            ['2015-02-10', 'M', 'MR', 3, 0],
            ['2015-02-01', 'S', 'MR', 0, 0],
            ['2015-02-10', 'M', 'LP', 4.90, 0],
        ];
    }
}
