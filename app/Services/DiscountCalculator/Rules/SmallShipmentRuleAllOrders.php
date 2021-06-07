<?php

namespace App\Services\DiscountCalculator\Rules;

use Illuminate\Support\Carbon;
use App\Entities\ShipmentDiscount;
use App\Services\DiscountCalculator\Rules\Interfaces\ShipmentDiscountRuleInterface;

/**
 * Rule that is apply to all the orders
 */
class SmallShipmentRuleAllOrders implements ShipmentDiscountRuleInterface
{
    const MR_DISCOUNT = 0.50;
    
    /**
     * @param ShipmentDiscount $shipmentDiscount
     * 
     * @return ShipmentDiscount
     * 
     */
    public function calculateShipmentDiscount(ShipmentDiscount $shipmentDiscount)
    {
        $lowestPrice = 0;

        $orders = $shipmentDiscount->getOrders();

        foreach ($orders as $key => $order) {
            if ($order['size'] !== ShipmentDiscountRuleInterface::SMALL_SIZE) {
                continue;
            }

            $discount = 0;

            $price = ShipmentDiscountRuleInterface::SHIPPING_PRICE[$order['provider']][$order['size']] - $discount;

            if ($lowestPrice === 0 || $price < $lowestPrice) {
                $lowestPrice = $price;
            }

            if ($order['provider'] === ShipmentDiscountRuleInterface::MR_PROVIDER) {
                $discount = self::MR_DISCOUNT;
            }

            $orders[$key]['price'] = $price;
            $orders[$key]['discount'] = $discount;
        }

        $orders = $this->setLowerPrice($orders, $lowestPrice);

        $shipmentDiscount->setOrders($orders);

        return $shipmentDiscount;
    }

    /**
     * @param array $orders
     * @param float $lowestPrice
     * 
     * @return array
     */
    private function setLowerPrice(array $orders, float $lowestPrice): array
    {
        $setPrice = function(&$order, $key, $lowestPrice) {

            if ($order['size'] === ShipmentDiscountRuleInterface::SMALL_SIZE) {
                $order['price'] = $lowestPrice;
            }
        };

        array_walk($orders, $setPrice, $lowestPrice);

        return $orders;
    }

    private function getLowestPrice(array $orders) 
    {
        return min(array_column($orders, 'price'));
    }
}
