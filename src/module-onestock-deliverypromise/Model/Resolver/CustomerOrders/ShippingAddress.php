<?php

declare(strict_types=1);

namespace Smile\OnestockDeliveryPromise\Model\Resolver\CustomerOrders;

use InvalidArgumentException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Sales\Model\Order;

class ShippingAddress implements ResolverInterface
{
    public function __construct(protected Json $serializer,)
    {
    }

    /**
     * Add onestockdp on customer order address graphQL
     */
    public function resolve(
        Field $field,
        ContextInterface $context,
        ResolveInfo $info,
        ?array $value = null,
        ?array $args = null
    ): array {
        if (!isset($value['model']) && !($value['model'] instanceof Order)) {
            throw new LocalizedException(__('"model" value should be specified'));
        }
        $unseraliazedShippingAddress = [];
        try {
            $unseraliazedShippingAddress =
                $this->serializer->unserialize($value['model']
                    ->getShippingAddress()->getOnestockDp());
        } catch (InvalidArgumentException $e) { // phpcs:ignore Magento2.CodeAnalysis.EmptyBlock.DetectedCatch
        }
        return $value['model']->getShippingAddress()->getData() + $unseraliazedShippingAddress;
    }
}
