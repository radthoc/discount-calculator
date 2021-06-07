<?php

namespace App\Services\DiscountCalculator\Interfaces;

use App\Entities\ShipmentDiscount;

interface DiscountCalculatorInterface
{
    /**
     * @param string $fileName
     *
     * @return ShipmentDiscount
     */
    public function calculateDiscounts(string $fileName);
}
