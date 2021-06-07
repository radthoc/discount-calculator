<?php

namespace Tests\Unit\Rules;

use Tests\TestCase;
use Illuminate\Support\Carbon;
use App\Entities\ShipmentDiscount;
use App\Services\DiscountCalculator\DiscountCalculator;


class DiscountCalculatorTest extends TestCase
{
    public function testDiscountCalculator()
    {
        $fileName = 'input.txt';

        $discountCalculator = $this->app->make(DiscountCalculator::class);

        $shipmentDiscount = $discountCalculator->calculateDiscounts($fileName);

        $this->assertEquals($this->getExpectedOrdersResult(), $shipmentDiscount->getOrders());
    }

    private function getExpectedOrdersResult()
    {
        return [
            [
                'date' => '2015-02-01',
                'size' => 'S',
                'provider' => 'MR',
                'price' => 1.5,
                'discount' => 0.5,
            ],[
                'date' => '2015-02-02',
                'size' => 'S',
                'provider' => 'MR',
                'price' => 1.5,
                'discount' => 0.5,
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
                'price' => 1.5,
                'discount' => 0,
            ],[
                'date' => '2015-02-06',
                'size' => 'S',
                'provider' => 'MR',
                'price' => 1.5,
                'discount' => 0.5,
            ],[
                'date' => '2015-02-06',
                'size' => 'L',
                'provider' => 'LP',
                'price' => 6.9,
                'discount' => 0,
            ],[
                'date' => '2015-02-07',
                'size' => 'L',
                'provider' => 'MR',
                'price' => 4,
                'discount' => 0,
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
                'size' => 'L',
                'provider' => 'LP',
                'price' => 6.9,
                'discount' => 0,
            ],[
                'date' => '2015-02-10',
                'size' => 'S',
                'provider' => 'MR',
                'price' => 1.5,
                'discount' => 0.5,
            ],[
                'date' => '2015-02-10',
                'size' => 'S',
                'provider' => 'MR',
                'price' => 1.5,
                'discount' => 0.5,
            ],[
                'date' => '2015-02-11',
                'size' => 'L',
                'provider' => 'LP',
                'price' => 6.9,
                'discount' => 0,
            ],[
                'date' => '2015-02-12',
                'size' => 'M',
                'provider' => 'MR',
                'price' => 3,
                'discount' => 0,
            ],[
                'date' => '2015-02-13',
                'size' => 'M',
                'provider' => 'LP',
                'price' => 4.9,
                'discount' => 0,
            ],[
                'date' => '2015-02-15',
                'size' => 'S',
                'provider' => 'MR',
                'price' => 1.5,
                'discount' => 0.5,
            ],[
                'date' => '2015-02-17',
                'size' => 'L',
                'provider' => 'LP',
                'price' => 6.9,
                'discount' => 0,
            ],[
                'date' => '2015-02-17',
                'size' => 'S',
                'provider' => 'MR',
                'price' => 1.5,
                'discount' => 0,
            ],[
                'date' => '2015-02-24',
                'size' => 'L',
                'provider' => 'LP',
                'price' => 6.9,
                'discount' => 0,
            ],[
                'date' => '2015-03-01',
                'size' => 'S',
                'provider' => 'MR',
                'price' => 1.5,
                'discount' => 0.5,
            ]
        ];
    }
}