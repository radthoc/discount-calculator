<?php

namespace App\Services\DiscountCalculator\Rules;

use Illuminate\Support\Carbon;
use App\Entities\ShipmentDiscount;
use App\Services\DiscountCalculator\Rules\Interfaces\ShipmentDiscountRuleInterface;

class LargeShipmentRuleAllOrders implements ShipmentDiscountRuleInterface
{
    const ORDER_NUMBER_LP_FREE = 3;

    /**
     * @param ShipmentDiscount $shipmentDiscount
     *
     * @return ShipmentDiscount
     *
     */
    public function calculateShipmentDiscount(ShipmentDiscount $shipmentDiscount)
    {
        $lastDate = null;
        $numberOfLPOrders = 0;
                
        $orders = $shipmentDiscount->getOrders();

        foreach ($orders as $key => $order) {
            if ($order['size'] !== ShipmentDiscountRuleInterface::LARGE_SIZE) {
                continue;
            }

            $discount = 0;

            $orderDate = Carbon::createFromFormat('Y-m-d', $order['date']);

            if (!$lastDate) {
                $lastDate = $orderDate;
            }

            if ($lastDate && !$lastDate->isSameMonth($orderDate)) {
                $lastDate = $orderDate;
                $numberOfLPOrders = 0;
            }
        
            $price = ShipmentDiscountRuleInterface::SHIPPING_PRICE[$order['provider']][$order['size']];
        
            if ($lastDate->isSameMonth($orderDate) &&
                $order['provider'] === ShipmentDiscountRuleInterface::LP_PROVIDER
            ) {
                $numberOfLPOrders += 1;
            
                if ($numberOfLPOrders === self::ORDER_NUMBER_LP_FREE) {
                    $discount = $price;
                }
            }

            $orders[$key]['price'] = $price - $discount;
            $orders[$key]['discount'] = $discount;
        }

        $shipmentDiscount->setOrders($orders);

        return $shipmentDiscount;
    }
}
