<?php

namespace Tests\Unit\Rules;

use Tests\TestCase;
use Illuminate\Support\Carbon;
use App\Entities\ShipmentDiscount;
use App\Services\DiscountCalculator\Rules\SmallShipmentRuleAllOrders;

class SmallShipmentRuleAllOrdersTest extends TestCase
{
    public function testOrdersSmallShipment()
    {
        $expectedResult = [];

        $smallShipmentRule = $this->app->make(SmallShipmentRuleAllOrders::class);

        $shipmentDiscount = new ShipmentDiscount();
        
        $orders = $this->getOrders();

        foreach ($orders as $order) {
            $shipmentDiscount->addOrder(
                $order[0],
                $order[1],
                $order[2]
            );
        }

        $shipmentDiscount = $smallShipmentRule->calculateShipmentDiscount($shipmentDiscount);

        $this->assertEquals($this->getExpectedOrders(), $shipmentDiscount->getOrders());
    }

    private function getOrders()
    {
        return [
            ['2015-02-01', 'S', 'MR'],
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
            ['2015-02-10', 'S', 'MR'],
        ];
    }

    private function getExpectedOrders()
    {
        return [
            [
                "date" => "2015-02-01",
                "size" => "S",
                "provider" => "MR",
                "price" => 1.5,
                "discount" => 0.5,
                'valid' => true,
            ],[
                "date" => "2015-02-02",
                "size" => "S",
                "provider" => "MR",
                "price" => 1.5,
                "discount" => 0.5,
                'valid' => true,
            ],[
                "date" => "2015-02-03",
                "size" => "L",
                "provider" => "LP",
                "price" => 0,
                "discount" => 0,
                'valid' => true,
            ],[
                "date" => "2015-02-05",
                "size" => "S",
                "provider" => "LP",
                "price" => 1.5,
                "discount" => 0,
                'valid' => true,
            ],[
                "date" => "2015-02-06",
                "size" => "S",
                "provider" => "MR",
                "price" => 1.5,
                "discount" => 0.5,
                'valid' => true,
            ],[
                "date" => "2015-02-06",
                "size" => "L",
                "provider" => "LP",
                "price" => 0,
                "discount" => 0,
                'valid' => true,
            ],[
                "date" => "2015-02-07",
                "size" => "L",
                "provider" => "MR",
                "price" => 0,
                "discount" => 0,
                'valid' => true,
            ],[
                "date" => "2015-02-08",
                "size" => "M",
                "provider" => "MR",
                "price" => 0,
                "discount" => 0,
                'valid' => true,
            ],[
                "date" => "2015-02-09",
                "size" => "L",
                "provider" => "LP",
                "price" => 0,
                "discount" => 0,
                'valid' => true,
            ],[
                "date" => "2015-02-10",
                "size" => "L",
                "provider" => "LP",
                "price" => 0,
                "discount" => 0,
                'valid' => true,
            ],[
                "date" => "2015-02-10",
                "size" => "S",
                "provider" => "MR",
                "price" => 1.5,
                "discount" => 0.5,
                'valid' => true,
            ],[
                "date" => "2015-02-10",
                "size" => "S",
                "provider" => "MR",
                "price" => 1.5,
                "discount" => 0.5,
                'valid' => true,
            ]
        ];
    }
}
