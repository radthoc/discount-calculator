<?php

namespace Tests\Unit\Rules;

use Tests\TestCase;
use Illuminate\Support\Carbon;
use App\Entities\ShipmentDiscount;
use App\Services\DiscountCalculator\Rules\SmallShipmentRuleAllOrders;
use App\Services\DiscountCalculator\Rules\MediumShipmentRulePerOrder;
use App\Services\DiscountCalculator\Rules\LargeShipmentRuleAllOrders;
use App\Services\DiscountCalculator\Rules\AcummulatedDiscountsRuleAllOrders;

class AllDiscountRulesTest extends TestCase
{
    public function testAllDiscountRules()
    {
        $expectedResult = [];

        $smallShipmentRule = $this->app->make(SmallShipmentRuleAllOrders::class);
        $mediumShipmentRule = $this->app->make(MediumShipmentRulePerOrder::class);
        $largeShipmentRule = $this->app->make(LargeShipmentRuleAllOrders::class);
        $acummulatedDiscountsRule = $this->app->make(AcummulatedDiscountsRuleAllOrders::class);

        $shipmentDiscount = new ShipmentDiscount();
        
        $orders = $this->getOrders();

        foreach ($orders as $order) {
            $date = $order[0] ?? '';
            $size = $order[1] ?? '';
            $provider = $order[2] ?? '';

            $shipmentDiscount->addOrder(
                $date,
                $size,
                $provider
            );

            //Rules that don't need state and can process one order at a time
            $shipmentDiscount = $mediumShipmentRule->calculateShipmentDiscount($shipmentDiscount);
        }

        //Rules that need some state, and must get process the orders
        $shipmentDiscount = $smallShipmentRule->calculateShipmentDiscount($shipmentDiscount);
        $shipmentDiscount = $largeShipmentRule->calculateShipmentDiscount($shipmentDiscount);
        $shipmentDiscount = $acummulatedDiscountsRule->calculateShipmentDiscount($shipmentDiscount);

        $this->assertEquals($this->getExpectedOrders(), $shipmentDiscount->getOrders());
    }

    private function getOrders()
    {
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
            ['2015-02-13', 'L', 'LP'],
            ['2015-02-16', 'S', 'MR'],
            ['2015-02-16', 'L', 'LP'],
            ['2015-02-17', 'L', 'MR'],
            ['2015-02-18', 'M', 'MR'],
            ['2015-03-02', 'L', 'LP'],
            ['2015-03-06', 'L', 'LP'],
            ['2015-03-07', 'L', 'MR'],
            ['2015-03-08', 'M', 'MR'],
            ['2015-03-09', 'L', 'LP'],
            ['2015-03-09', 'S', 'MR'],
            ['2015-03-10', 'S', 'MR'],
            ['2015-03-12', 'S', 'MR'],
            ['2015-03-12', 'S', 'MR'],
            ['2015-03-13', 'S', 'MR'],
            ['2015-03-14', 'S', 'MR'],
            ['2015-03-15', 'S', 'MR'],
            ['2015-03-16', 'S', 'MR'],
            ['2015-03-17', 'S', 'MR'],
            ['2015-03-29', 'CUSPS'],
        ];
    }

    private function getExpectedOrders($sameMonthOrders = true)
    {
        return [
            [
                'date' => '2015-02-02',
                'size' => 'S',
                'provider' => 'MR',
                'price' => 1.50,
                'discount' => 0.50,
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
                'price' => 1.50,
                'discount' => 0,
                'valid' => true,
            ],[
                'date' => '2015-02-06',
                'size' => 'S',
                'provider' => 'MR',
                'price' => 1.50,
                'discount' => 0.50,
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
                'price' => 3,
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
                'price' => 1.50,
                'discount' => 0.50,
                'valid' => true,
            ],[
                'date' => '2015-02-13',
                'size' => 'L',
                'provider' => 'LP',
                'price' => 6.9,
                'discount' => 0,
                'valid' => true,
            ],[
                'date' => '2015-02-16',
                'size' => 'S',
                'provider' => 'MR',
                'price' => 1.50,
                'discount' => 0.50,
                'valid' => true,
            ],[
                'date' => '2015-02-16',
                'size' => 'L',
                'provider' => 'LP',
                'price' => 6.9,
                'discount' => 0,
                'valid' => true,
            ],[
                'date' => '2015-02-17',
                'size' => 'L',
                'provider' => 'MR',
                'price' => 4,
                'discount' => 0,
                'valid' => true,
            ],[
                'date' => '2015-02-18',
                'size' => 'M',
                'provider' => 'MR',
                'price' => 3,
                'discount' => 0,
                'valid' => true,
            ],[
                'date' => '2015-03-02',
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
                'price' => 3,
                'discount' => 0,
                'valid' => true,
            ],[
                'date' => '2015-03-09',
                'size' => 'L',
                'provider' => 'LP',
                'price' => 0.0,
                'discount' => 6.9,
                'valid' => true,
            ],[
                'date' => '2015-03-09',
                'size' => 'S',
                'provider' => 'MR',
                'price' => 1.50,
                'discount' => 0.50,
                'valid' => true,
            ],[
                'date' => '2015-03-10',
                'size' => 'S',
                'provider' => 'MR',
                'price' => 1.50,
                'discount' => 0.50,
                'valid' => true,
            ],[
                'date' => '2015-03-12',
                'size' => 'S',
                'provider' => 'MR',
                'price' => 1.50,
                'discount' => 0.50,
                'valid' => true,
            ],[
                'date' => '2015-03-12',
                'size' => 'S',
                'provider' => 'MR',
                'price' => 1.50,
                'discount' => 0.50,
                'valid' => true,
            ],[
                'date' => '2015-03-13',
                'size' => 'S',
                'provider' => 'MR',
                'price' => 1.50,
                'discount' => 0.50,
                'valid' => true,
            ],[
                'date' => '2015-03-14',
                'size' => 'S',
                'provider' => 'MR',
                'price' => 1.50,
                'discount' => 0.50,
                'valid' => true,
            ],[
                'date' => '2015-03-15',
                'size' => 'S',
                'provider' => 'MR',
                'price' => 1.50,
                'discount' => 0,
                'valid' => true,
            ],[
                'date' => '2015-03-16',
                'size' => 'S',
                'provider' => 'MR',
                'price' => 1.50,
                'discount' => 0,
                'valid' => true,
            ],[
                'date' => '2015-03-17',
                'size' => 'S',
                'provider' => 'MR',
                'price' => 1.50,
                'discount' => 0,
                'valid' => true,
            ],
            [
                'date' => '2015-03-29', 
                'size' => 'CUSPS',
                'provider' => '',
                'price' => 0,
                'discount' => 0,
                'valid' => false,
            ]
        ];
    }
}
