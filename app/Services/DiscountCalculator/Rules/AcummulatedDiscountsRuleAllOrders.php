<?php

namespace App\Services\DiscountCalculator\Rules;

use Illuminate\Support\Carbon;
use App\Entities\ShipmentDiscount;
use App\Services\DiscountCalculator\Rules\Interfaces\ShipmentDiscountRuleInterface;

class AcummulatedDiscountsRuleAllOrders implements ShipmentDiscountRuleInterface
{
    const ACUMMULATED_DISCOUNTS_LIMIT = 10;

    /**
     * @param ShipmentDiscount $shipmentDiscount
     *
     * @return ShipmentDiscount
     *
     */
    public function calculateShipmentDiscount(ShipmentDiscount $shipmentDiscount)
    {
        $lastDate = null;
        $acummulatedDiscounts = 0;
        
        $orders = $shipmentDiscount->getOrders();

        foreach ($orders as $key => $order) {
            $orderDate = Carbon::createFromFormat('Y-m-d', $order['date']);

            if (!$lastDate) {
                $lastDate = $orderDate;
            }

            if ($lastDate && !$lastDate->isSameMonth($orderDate)) {
                $lastDate = $orderDate;
                $acummulatedDiscounts = 0;
            }

            if ($lastDate->isSameMonth($orderDate)) {
                $acummulatedDiscounts += $order['discount'];
            }

            if ($acummulatedDiscounts > self::ACUMMULATED_DISCOUNTS_LIMIT) {
                $orders[$key]['discount'] = 0;
            }
        }

        $shipmentDiscount->setOrders($orders);

        return $shipmentDiscount;
    }
}
