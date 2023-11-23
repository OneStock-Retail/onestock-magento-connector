<?php

declare(strict_types=1);

namespace Smile\OnestockDeliveryPromise\Plugin;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Quote\Model\Quote\Address;
use Magento\Quote\Model\Quote\Address\Rate;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Psr\Log\LoggerInterface;
use Smile\Onestock\Helper\CacheToken;
use Smile\Onestock\Helper\Mapping;
use Smile\OnestockDeliveryPromise\Api\Data\PromiseInterface;
use Smile\OnestockDeliveryPromise\Helper\Config;
use Smile\OnestockDeliveryPromise\Model\Request\Promise as Request;

class AddPromiseToRate
{
    public function __construct(
        protected LoggerInterface $logger,
        protected Request $request,
        protected CacheToken $tokenHelper,
        protected Mapping $mapping,
        protected Config $config,
    ) {
    }

    /**
     * Add onestock_dp to all quote_shipping_rate(s)
     * and the rate corresponding to the shipping method to quote_address
     * if shipping method is set
     */
    public function aroundRequestShippingRates(Address $subject, callable $proceed, ?AbstractItem $item = null): bool
    {
        $found = $proceed($item);

        if (!$this->config->isEnabled()) {
            return $found;
        }
        $shippingRates = $subject->getShippingRatesCollection();
        $methods = [];
        foreach ($shippingRates as $rate) {
            $methods[$rate->getCode()] = $rate;
        }
        if (empty($methods)) {
            return $found;
        }
        
        $products = [$item];
        $requests = [];
        if ($item == null) {
            $products = $subject->getAllItems();
        }
        foreach ($products as $product) {
            $requests[] = [
                "item_id" => $product->getSku(),
                "qty" => $product->getQty(),
            ];
        }
        foreach ($this->getPromises($requests, array_keys($methods), $subject->getCountryId(), $subject->getPostcode()??"") as $promise) {
            if (!isset($methods[$promise->getDeliveryMethod()])) {
                continue;
            }
            /** @var Rate $rate */
            $rate = $methods[$promise->getDeliveryMethod()];
            $rate->setOnestockDp((string) $promise);

            if ($subject->getShippingMethod() == $promise->getDeliveryMethod()) {
                $subject->setOnestockDp((string) $promise);
            }
        }
        return $found;
    }

    /**
     * Retrieve promise for quote
     *
     * @param array<int, array<string, mixed>> $items
     * @param string[] $methods
     * @return PromiseInterface[]
     * @throws GuzzleException
     */
    protected function getPromises(array $items, array $methods, string $country, string $postcode): array
    {
        $res = [];
        try {
            /** @var PromiseInterface[] $res */
            $res = $this->tokenHelper->call(function ($config, $token) use ($items, $methods, $country, $postcode): array {
                return $this->request->get($config, $token, $items, $methods, $country, $postcode);
            });
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }
        return $res;
    }
}
