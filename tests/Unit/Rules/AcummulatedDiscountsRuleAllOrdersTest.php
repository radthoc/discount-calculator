<?php

namespace Tests\Unit\Rules;

use Tests\TestCase;
use Illuminate\Support\Carbon;
use App\Entities\ShipmentDiscount;
use App\Services\DiscountCalculator\Rules\AcummulatedDiscountsRuleAllOrders;

class AcummulatedDiscountsRuleAllOrdersTest extends TestCase
{
    public function testAcummulatedDiscountGreaterThanLimit()
    {
        $expectedResult = [];

        $acummulatedDiscountsRule = $this->app->make(AcummulatedDiscountsRuleAllOrders::class);

        $shipmentDiscount = new ShipmentDiscount();
        
        $shipmentDiscount->setOrders($this->getOrders());

        $shipmentDiscount = $acummulatedDiscountsRule->calculateShipmentDiscount($shipmentDiscount);

        $this->assertEquals($this->getExpectedOrders(), $shipmentDiscount->getOrders());
    }

    public function testAcummulatedDiscountWithDifferentMonth()
    {
        $expectedResult = [];

        $acummulatedDiscountsRule = $this->app->make(AcummulatedDiscountsRuleAllOrders::class);

        $shipmentDiscount = new ShipmentDiscount();
        
        $shipmentDiscount->setOrders($this->getOrders(false));

        $shipmentDiscount = $acummulatedDiscountsRule->calculateShipmentDiscount($shipmentDiscount);

        $this->assertEquals($this->getExpectedOrders(false), $shipmentDiscount->getOrders());

    }

    private function getOrders($sameMonthOrders = true)
    {
        if ($sameMonthOrders) {
            return [
                [
                    'date' => '2015-02-02',
                    'size' => 'S',
                    'provider' => 'MR',
                    'price' => 1.50,
                    'discount' => 0.50,
                ],[
                    'date' => '2015-02-03',
                    'size' => 'L',
                    'provider' => 'LP',
                    'price' => 6.9,
                    'discount' => 0,
                ],[
                    'date' => '2015-02-05',
                    'size' => 'S',
                    'provider' => 'LP',
                    'price' => 1.50,
                    'discount' => 0,
                ],[
                    'date' => '2015-02-06',
                    'size' => 'S',
                    'provider' => 'MR',
                    'price' => 1.50,
                    'discount' => 0.50,
                ],[
                    'date' => '2015-02-06',
                    'size' => 'S',
                    'provider' => 'MR',
                    'price' => 1.50,
                    'discount' => 0.50,
                ],[
                    'date' => '2015-02-06',
                    'size' => 'L',
                    'provider' => 'LP',
                    'price' => 6.9,
                    'discount' => 0,
                ],[
                    'date' => '2015-02-07',
                    'size' => 'S',
                    'provider' => 'MR',
                    'price' => 1.50,
                    'discount' => 0.50,
                ],[
                    'date' => '2015-02-07',
                    'size' => 'L',
                    'provider' => 'MR',
                    'price' => 4,
                    'discount' => 0,
                ],[
                    'date' => '2015-02-07',
                    'size' => 'S',
                    'provider' => 'MR',
                    'price' => 1.50,
                    'discount' => 0.50,
                ],[
                    'date' => '2015-02-08',
                    'size' => 'M',
                    'provider' => 'MR',
                    'price' => 3,
                    'discount' => 0,
                ],[
                    'date' => '2015-02-09',
                    'size' => 'L',
                    'provider' => 'LP',
                    'price' => 0.0,
                    'discount' => 6.9,
                ],[
                    'date' => '2015-02-10',
                    'size' => 'S',
                    'provider' => 'LP',
                    'price' => 1.50,
                    'discount' => 0,
                ],[
                    'date' => '2015-02-10',
                    'size' => 'S',
                    'provider' => 'MR',
                    'price' => 1.50,
                    'discount' => 0.50,
                ],[
                    'date' => '2015-02-10',
                    'size' => 'S',
                    'provider' => 'MR',
                    'price' => 1.50,
                    'discount' => 0.50,
                ],[
                    'date' => '2015-02-12',
                    'size' => 'S',
                    'provider' => 'MR',
                    'price' => 1.50,
                    'discount' => 0.50,
                ],
            ];
        }

        return [
            [
                'date' => '2015-02-02',
                'size' => 'S',
                'provider' => 'MR',
                'price' => 1.50,
                'discount' => 0.50,
            ],[
                'date' => '2015-02-03',
                'size' => 'L',
                'provider' => 'LP',
                'price' => 6.9,
                'discount' => 0,
            ],[
                'date' => '2015-02-05',
                'size' => 'S',
                'provider' => 'LP',
                'price' => 1.50,
                'discount' => 0,
            ],[
                'date' => '2015-02-06',
                'size' => 'S',
                'provider' => 'MR',
                'price' => 1.50,
                'discount' => 0.50,
            ],[
                'date' => '2015-02-06',
                'size' => 'S',
                'provider' => 'MR',
                'price' => 1.50,
                'discount' => 0.50,
            ],[
                'date' => '2015-02-06',
                'size' => 'L',
                'provider' => 'LP',
                'price' => 6.9,
                'discount' => 0,
            ],[
                'date' => '2015-02-07',
                'size' => 'S',
                'provider' => 'MR',
                'price' => 1.50,
                'discount' => 0.50,
            ],[
                'date' => '2015-02-07',
                'size' => 'L',
                'provider' => 'MR',
                'price' => 4,
                'discount' => 0,
            ],[
                'date' => '2015-02-07',
                'size' => 'S',
                'provider' => 'MR',
                'price' => 1.50,
                'discount' => 0.50,
            ],[
                'date' => '2015-02-08',
                'size' => 'M',
                'provider' => 'MR',
                'price' => 3,
                'discount' => 0,
            ],[
                'date' => '2015-02-09',
                'size' => 'L',
                'provider' => 'LP',
                'price' => 0.0,
                'discount' => 6.9,
            ],[
                'date' => '2015-02-10',
                'size' => 'S',
                'provider' => 'LP',
                'price' => 1.50,
                'discount' => 0,
            ],[
                'date' => '2015-03-01',
                'size' => 'S',
                'provider' => 'MR',
                'price' => 1.50,
                'discount' => 0.50,
            ],[
                'date' => '2015-03-02',
                'size' => 'S',
                'provider' => 'MR',
                'price' => 1.50,
                'discount' => 0.50,
            ],[
                'date' => '2015-03-02',
                'size' => 'S',
                'provider' => 'MR',
                'price' => 1.50,
                'discount' => 0.50,
            ],
        ];
    }

    private function getExpectedOrders($sameMonthOrders = true)
    {
        if ($sameMonthOrders) {
            return [
                [
                    'date' => '2015-02-02',
                    'size' => 'S',
                    'provider' => 'MR',
                    'price' => 1.50,
                    'discount' => 0.50,
                ],[
                    'date' => '2015-02-03',
                    'size' => 'L',
                    'provider' => 'LP',
                    'price' => 6.9,
                    'discount' => 0,
                ],[
                    'date' => '2015-02-05',
                    'size' => 'S',
                    'provider' => 'LP',
                    'price' => 1.50,
                    'discount' => 0,
                ],[
                    'date' => '2015-02-06',
                    'size' => 'S',
                    'provider' => 'MR',
                    'price' => 1.50,
                    'discount' => 0.50,
                ],[
                    'date' => '2015-02-06',
                    'size' => 'S',
                    'provider' => 'MR',
                    'price' => 1.50,
                    'discount' => 0.50,
                ],[
                    'date' => '2015-02-06',
                    'size' => 'L',
                    'provider' => 'LP',
                    'price' => 6.9,
                    'discount' => 0,
                ],[
                    'date' => '2015-02-07',
                    'size' => 'S',
                    'provider' => 'MR',
                    'price' => 1.50,
                    'discount' => 0.50,
                ],[
                    'date' => '2015-02-07',
                    'size' => 'L',
                    'provider' => 'MR',
                    'price' => 4,
                    'discount' => 0,
                ],[
                    'date' => '2015-02-07',
                    'size' => 'S',
                    'provider' => 'MR',
                    'price' => 1.50,
                    'discount' => 0.50,
                ],[
                    'date' => '2015-02-08',
                    'size' => 'M',
                    'provider' => 'MR',
                    'price' => 3,
                    'discount' => 0,
                ],[
                    'date' => '2015-02-09',
                    'size' => 'L',
                    'provider' => 'LP',
                    'price' => 0.0,
                    'discount' => 6.9,
                ],[
                    'date' => '2015-02-10',
                    'size' => 'S',
                    'provider' => 'LP',
                    'price' => 1.50,
                    'discount' => 0,
                ],[
                    'date' => '2015-02-10',
                    'size' => 'S',
                    'provider' => 'MR',
                    'price' => 1.50,
                    'discount' => 0.50,
                ],[
                    'date' => '2015-02-10',
                    'size' => 'S',
                    'provider' => 'MR',
                    'price' => 1.50,
                    'discount' => 0,
                ],[
                    'date' => '2015-02-12',
                    'size' => 'S',
                    'provider' => 'MR',
                    'price' => 1.50,
                    'discount' => 0,
                ],
            ];
        }

        return [
            [
                'date' => '2015-02-02',
                'size' => 'S',
                'provider' => 'MR',
                'price' => 1.50,
                'discount' => 0.50,                
            ],[
                'date' => '2015-02-03',
                'size' => 'L',
                'provider' => 'LP',
                'price' => 6.9,
                'discount' => 0,
            ],[
                'date' => '2015-02-05',
                'size' => 'S',
                'provider' => 'LP',
                'price' => 1.50,
                'discount' => 0,
            ],[
                'date' => '2015-02-06',
                'size' => 'S',
                'provider' => 'MR',
                'price' => 1.50,
                'discount' => 0.50,
            ],[
                'date' => '2015-02-06',
                'size' => 'S',
                'provider' => 'MR',
                'price' => 1.50,
                'discount' => 0.50,
            ],[
                'date' => '2015-02-06',
                'size' => 'L',
                'provider' => 'LP',
                'price' => 6.9,
                'discount' => 0,
            ],[
                'date' => '2015-02-07',
                'size' => 'S',
                'provider' => 'MR',
                'price' => 1.50,
                'discount' => 0.50,
            ],[
                'date' => '2015-02-07',
                'size' => 'L',
                'provider' => 'MR',
                'price' => 4,
                'discount' => 0,
            ],[
                'date' => '2015-02-07',
                'size' => 'S',
                'provider' => 'MR',
                'price' => 1.50,
                'discount' => 0.50,
            ],[
                'date' => '2015-02-08',
                'size' => 'M',
                'provider' => 'MR',
                'price' => 3,
                'discount' => 0,
            ],[
                'date' => '2015-02-09',
                'size' => 'L',
                'provider' => 'LP',
                'price' => 0.0,
                'discount' => 6.9,
            ],[
                'date' => '2015-02-10',
                'size' => 'S',
                'provider' => 'LP',
                'price' => 1.50,
                'discount' => 0,
            ],[
                'date' => '2015-03-01',
                'size' => 'S',
                'provider' => 'MR',
                'price' => 1.50,
                'discount' => 0.50,
            ],[
                'date' => '2015-03-02',
                'size' => 'S',
                'provider' => 'MR',
                'price' => 1.50,
                'discount' => 0.50,
            ],[
                'date' => '2015-03-02',
                'size' => 'S',
                'provider' => 'MR',
                'price' => 1.50,
                'discount' => 0.50,
            ],
        ];
    }
}
