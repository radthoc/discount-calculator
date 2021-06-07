<?php

namespace App\Services\DiscountCalculator;

use App;
use App\Services\Interfaces\FileServiceInterface;
use App\Services\DiscountCalculator\Interfaces\DiscountCalculatorInterface;
use App\Services\DiscountCalculator\Rules\SmallShipmentRuleAllOrders;
use App\Services\DiscountCalculator\Rules\MediumShipmentRulePerOrder;
use App\Services\DiscountCalculator\Rules\LargeShipmentRuleAllOrders;
use App\Services\DiscountCalculator\Rules\AcummulatedDiscountsRuleAllOrders;
use App\Entities\ShipmentDiscount;
use App\Services\FileService;

class DiscountCalculator implements DiscountCalculatorInterface
{
    /** @var FileserviceInterface */
    private $fileService;

    private $perOrderRules = [];
    private $allOrdersRules = [];

    public function __construct(FileserviceInterface $fileService)
    {
        $this->filseService = $fileService;

        $this->fileService = App::make(FileService::class);

        $this->addRules();
    }

    /**
     * @param string $fileName
     *
     * @return ShipmentDiscount
     */
    public function calculateDiscounts(string $filename)
    {
        $fileName = base_path() . DIRECTORY_SEPARATOR . 'input.txt';

        $fileIterator = $this->getFileIterator($fileName);

        $shipmentDiscount = new ShipmentDiscount();

        foreach ($fileIterator as $orderRow) {
            $order = $this->fileService->getRowColumns($orderRow);

            $date = $order[0] ?? '';
            $size = $order[1] ?? '';
            $provider = $order[2] ?? '';

            $shipmentDiscount->addOrder(
                $date,
                $size,
                $provider
            );

            //Rules that don't need state and can process one order at a time
            $shipmentDiscount = $this->executePerOrderRules($shipmentDiscount);
        }

        $shipmentDiscount = $this->executeAllOrdersRules($shipmentDiscount);

        return $shipmentDiscount;
    }

    private function addRules()
    {
        $this->perOrderRules[] = App::make(MediumShipmentRulePerOrder::class);

        $this->allOrdersRules = [
            App::make(SmallShipmentRuleAllOrders::class),
            App::make(LargeShipmentRuleAllOrders::class),
            App::make(AcummulatedDiscountsRuleAllOrders::class),
        ];
        ;
    }

    private function getFileIterator(string $fileName)
    {
        return $this->fileService->readfile($fileName);
    }

    private function executePerOrderRules(ShipmentDiscount $shipmentDiscount)
    {
        foreach ($this->perOrderRules as $perOrderRule) {
            $shipmentDiscount = $perOrderRule->calculateShipmentDiscount($shipmentDiscount);
        }

        return $shipmentDiscount;
    }

    private function executeAllOrdersRules(ShipmentDiscount $shipmentDiscount)
    {
        foreach ($this->allOrdersRules as $allOrdersRule) {
            $shipmentDiscount = $allOrdersRule->calculateShipmentDiscount($shipmentDiscount);
        }

        return $shipmentDiscount;
    }
}
