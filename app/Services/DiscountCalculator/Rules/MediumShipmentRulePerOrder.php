<?php

namespace App\Services\DiscountCalculator\Rules;

use Illuminate\Support\Carbon;
use App\Entities\ShipmentDiscount;
use App\Services\DiscountCalculator\Rules\Interfaces\ShipmentDiscountRuleInterface;

/**
 * Rule that is applied per order
 */
class MediumShipmentRulePerOrder implements ShipmentDiscountRuleInterface
{
    /**
     * @param ShipmentDiscount $shipmentDiscount
     *
     * @return ShipmentDiscount
     *
     */
    public function calculateShipmentDiscount(ShipmentDiscount $shipmentDiscount)
    {
        $discount = 0;
        
        $order = $shipmentDiscount->getCurrentOrder();

        if ($order['size'] !== ShipmentDiscountRuleInterface::MEDIUM_SIZE) {
            return $shipmentDiscount;
        }

        $shipmentDiscount->setShipmentPriceCurrentoOrder(
            ShipmentDiscountRuleInterface::SHIPPING_PRICE[$order['provider']][$order['size']]
        );

        $shipmentDiscount->setShipmentDiscountCurrentoOrder($discount);

        return $shipmentDiscount;
    }
}
