<?php

namespace App\Services\DiscountCalculator\Rules\Interfaces;

use App\Entities\ShipmentDiscount;

interface ShipmentDiscountRuleInterface
{
    const LP_PROVIDER = 'LP';
    const MR_PROVIDER = 'MR';

    const SMALL_SIZE = 'S';
    const MEDIUM_SIZE = 'M';
    const LARGE_SIZE = 'L';

    const SHIPPING_PRICE = [
        self::LP_PROVIDER => [
            self::SMALL_SIZE => 1.50,
            self::MEDIUM_SIZE => 4.90,
            self::LARGE_SIZE => 6.90,
        ],
        self::MR_PROVIDER => [
            self::SMALL_SIZE => 2,
            self::MEDIUM_SIZE => 3,
            self::LARGE_SIZE => 4,
        ],
    ];

    /**
     * @param ShipmentDiscount $shipmentDiscount
     * 
     * @return ShipmentDiscount
     * 
     */
    public function calculateShipmentDiscount(ShipmentDiscount $shiptmentDiscount);
}
