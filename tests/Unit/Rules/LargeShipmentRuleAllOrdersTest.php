<?php

namespace Tests\Unit\Rules;

use Tests\TestCase;
use Illuminate\Support\Carbon;
use App\Entities\ShipmentDiscount;
use App\Services\DiscountCalculator\Rules\LargeShipmentRuleAllOrders;

class LargeShipmentRuleAllOrdersTest extends TestCase
{
    public function testOrdersLargeShipment()
    {
        $expectedResult = [];

        $largeShipmentRule = $this->app->make(LargeShipmentRuleAllOrders::class);

        $shipmentDiscount = new ShipmentDiscount();
        
        $orders = $this->getOrders();

        foreach ($orders as $order) {
            $shipmentDiscount->addOrder(
                $order[0],
                $order[1],
                $order[2]
            );
        }

        $shipmentDiscount = $largeShipmentRule->calculateShipmentDiscount($shipmentDiscount);

        $this->assertEquals($this->getExpectedOrders(), $shipmentDiscount->getOrders());
    }

    public function testThirdOrderFromLpWithDifferentMonth()
    {
        $largeShipmentRule = $this->app->make(LargeShipmentRuleAllOrders::class);

        $shipmentDiscount = new ShipmentDiscount();

        $orders = $this->getOrders(false);

        foreach ($orders as $order) {
            $shipmentDiscount->addOrder(
                $order[0],
                $order[1],
                $order[2]
            );
        }

        $shipmentDiscount = $largeShipmentRule->calculateShipmentDiscount($shipmentDiscount);

        $this->assertEquals($this->getExpectedOrders(false), $shipmentDiscount->getOrders());
    }

    private function getOrders($sameMonthOrders = true)
    {
        if ($sameMonthOrders) {
            return [
                ['2015-02-02', 'S', 'MR'],
                ['2015-02-03', 'L', 'LP'],
                ['2015-02-05', 'S', 'LP'],
                ['2015-02-06', 'S', 'MR'],
                ['2015-02-06', 'L', 'LP'],
                ['2015-02-07', 'L', 'MR'],
                ['2015-02-08', 'M', 'MR'],
                ['2015-02-09', 'L', 'LP'],
                ['2015-02-10', 'L', 'LP'],
                ['2015-02-10', 'S', 'MR'],
            ];
        }

        return [
            ['2015-02-03', 'L', 'LP'],
            ['2015-02-06', 'S', 'MR'],
            ['2015-02-06', 'L', 'LP'],
            ['2015-02-07', 'L', 'MR'],
            ['2015-02-08', 'M', 'MR'],
            ['2015-03-09', 'L', 'LP'],
            ['2015-03-06', 'L', 'LP'],
            ['2015-03-07', 'L', 'MR'],
            ['2015-03-08', 'M', 'MR'],
            ['2015-03-09', 'L', 'LP'],
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
                    'price' => 0,
                    'discount' => 0,
                    'valid' => true,
                ],[
                    'date' => '2015-02-03',
                    'size' => 'L',
                    'provider' => 'LP',
                    'price' => 6.9,
                    'discount' => 0,
                    'valid' => true,
                ],[
                    'date' => '2015-02-05',
                    'size' => 'S',
                    'provider' => 'LP',
                    'price' => 0,
                    'discount' => 0,
                    'valid' => true,
                ],[
                    'date' => '2015-02-06',
                    'size' => 'S',
                    'provider' => 'MR',
                    'price' => 0,
                    'discount' => 0,
                    'valid' => true,
                ],[
                    'date' => '2015-02-06',
                    'size' => 'L',
                    'provider' => 'LP',
                    'price' => 6.9,
                    'discount' => 0,
                    'valid' => true,
                ],[
                    'date' => '2015-02-07',
                    'size' => 'L',
                    'provider' => 'MR',
                    'price' => 4,
                    'discount' => 0,
                    'valid' => true,
                ],[
                    'date' => '2015-02-08',
                    'size' => 'M',
                    'provider' => 'MR',
                    'price' => 0,
                    'discount' => 0,
                    'valid' => true,
                ],[
                    'date' => '2015-02-09',
                    'size' => 'L',
                    'provider' => 'LP',
                    'price' => 0.0,
                    'discount' => 6.9,
                    'valid' => true,
                ],[
                    'date' => '2015-02-10',
                    'size' => 'L',
                    'provider' => 'LP',
                    'price' => 6.9,
                    'discount' => 0,
                    'valid' => true,
                ],[
                    'date' => '2015-02-10',
                    'size' => 'S',
                    'provider' => 'MR',
                    'price' => 0,
                    'discount' => 0,
                    'valid' => true,
                ],
            ];
        }

        return [
            [
                'date' => '2015-02-03',
                'size' => 'L',
                'provider' => 'LP',
                'price' => 6.9,
                'discount' => 0,
                'valid' => true,
            ],[
                'date' => '2015-02-06',
                'size' => 'S',
                'provider' => 'MR',
                'price' => 0,
                'discount' => 0,
                'valid' => true,
            ],[
                'date' => '2015-02-06',
                'size' => 'L',
                'provider' => 'LP',
                'price' => 6.9,
                'discount' => 0,
                'valid' => true,
            ],[
                'date' => '2015-02-07',
                'size' => 'L',
                'provider' => 'MR',
                'price' => 4,
                'discount' => 0,
                'valid' => true,
            ],[
                'date' => '2015-02-08',
                'size' => 'M',
                'provider' => 'MR',
                'price' => 0,
                'discount' => 0,
                'valid' => true,
            ],[
                'date' => '2015-03-09',
                'size' => 'L',
                'provider' => 'LP',
                'price' => 6.9,
                'discount' => 0,
                'valid' => true,
            ],[
                'date' => '2015-03-06',
                'size' => 'L',
                'provider' => 'LP',
                'price' => 6.9,
                'discount' => 0,
                'valid' => true,
            ],[
                'date' => '2015-03-07',
                'size' => 'L',
                'provider' => 'MR',
                'price' => 4,
                'discount' => 0,
                'valid' => true,
            ],[
                'date' => '2015-03-08',
                'size' => 'M',
                'provider' => 'MR',
                'price' => 0,
                'discount' => 0,
                'valid' => true,
            ],[
                'date' => '2015-03-09',
                'size' => 'L',
                'provider' => 'LP',
                'price' => 0.0,
                'discount' => 6.9,
                'valid' => true,
            ]
        ];
    }
}
