<?php

declare(strict_types=1);

namespace Smile\OnestockDeliveryPromise\Plugin;

use Magento\Framework\Serialize\Serializer\Json;
use Magento\QuoteGraphQl\Model\Resolver\ShippingAddress\SelectedShippingMethod;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;

/**
 * Add Promise result To resolver selectedShippingAddress
 */
class AddPromiseToSelectedShippingAddress
{
    /**
     * @param Json $serializer
     */
    public function __construct(protected Json $serializer,)
    {
    }

    /**
     * @param SelectedShippingMethod $subject
     * @param callable $proceed
     * @param Field $field
     * @param ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return mixed
     */
    public function aroundResolve(SelectedShippingMethod $subject,
                                  callable $proceed,
                                  Field $field,
                                  ContextInterface $context,
                                  ResolveInfo $info,
                                  array $value = null,
                                  array $args = null): mixed
    {
        $address = $value['model'];
        $unseraliazedAdress = $this->serializer->unserialize($address->getOnestockDp());
        $result = $proceed($field, $context, $info, $value, $args );
        return array_merge($result, $unseraliazedAdress);
    }
}
