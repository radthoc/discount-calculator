<?php

namespace App\Entities;

use Illuminate\Support\Carbon;
use App\Services\DiscountCalculator\Rules\Interfaces\ShipmentDiscountRuleInterface;

class ShipmentDiscount
{
    private $orders = [];

    private $validationRules = [
        'date' => [
            'filter' => 'FILTER_VALIDATE_REGEXP',
            'options' => ['regexp' => "/\d{4}\-\d{2}-\d{2}/"],
        ],
        'size' => [
            'filter' => 'FILTER_VALIDATE_REGEXP',
            'options' => ['regexp' => "^(S|M|L)$"],
        ],
        'provider' => [
            'filter' => 'FILTER_VALIDATE_REGEXP',
            'options' => ['regexp' => "^(MR|LP)$"],
        ],
    ];

    public function addOrder(
        string $date,
        string $size,
        string $provider,
        $price = 0,
        $discount = 0
    ) {
        $valid = true;

        if (!$this->isValid([
            'date' => $date,
            'size' => $size,
            'provider' => $provider
            ])
        ) {
            $valid = false;
        }

        array_push($this->orders, [
            'date' => $date,
            'size' => $size,
            'provider' => $provider,
            'price' => $price,
            'discount' => $discount,
            'valid' => $valid,
        ]);
    }

    public function setShipmentPriceCurrentoOrder($price)
    {
        $this->orders[array_key_last($this->orders)]['price'] = $price;
    }

    public function setShipmentDiscountCurrentoOrder($discount)
    {
        $this->orders[array_key_last($this->orders)]['discount'] = $discount;
    }

    public function getCurrentOrder()
    {
        return end($this->orders);
    }

    public function setOrders($orders)
    {
        $this->orders = $orders;
    }

    public function getOrders()
    {
        return $this->orders;
    }

    private function isValid(): bool
    {
        foreach ($this->getCurrentOrder() as $column => $value) {
            if (filter_var(
                $value,
                $this->validationRules[$field]['filter'],
                [
                    'options' => $this->validationRules[$field]['options']
                ]
            ) === false
            ) {
                return false;
            }
        }

        return true;
    }
}
