<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade to newer
 * versions in the future.
 *
 * @author    Pascal Noisette <pascal.noisette@smile.fr>
 * @copyright 2023 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

declare(strict_types=1);

namespace Smile\Onestock\Observer\Mapping\Order;

use Magento\Framework\DataObject\Copy;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Locale\ListsInterface;

/**
 * Part of the mapping handling package value
 */
class Price implements ObserverInterface
{
    public function __construct(
        protected ListsInterface $translate,
        protected Copy $objectCopyService
    ) {
    }

    /**
     * Add order to export queue
     */
    public function execute(Observer $observer): void
    {
        $order = $observer->getSource();
        $target = $observer->getTarget();
        $address = $order->getBillingAddress();

        $target['pricing_details'] = [
            'currency' => $order->getOrderCurrencyCode(),
            'address' => [
                'lines' => $address->getStreet(),
                'city' => $address->getCity(),
                'zip_code' => $address->getPostcode(),
                'regions' => [
                    'country' => [
                        'code' => $address->getCountryId(),
                        'name' => $this->translate->getCountryTranslation($address->getCountryId()),
                    ],
                ],
                'contact' => array_filter(
                    $this->objectCopyService->getDataFromFieldset(
                        'onestock_address_mapping',
                        'to_onestock_contact',
                        $address,
                    )
                ),
            ],
            'price' => floatval($order->getGrandTotal()),
        ];
    }
}
