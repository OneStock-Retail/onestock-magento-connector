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
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Address;

/**
 * Part of the mapping handling shipping adress
 */
class Delivery implements ObserverInterface
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
        /** @var Order $order */
        $order = $observer->getSource();

        /** @var Address $address */
        $address = $order->getShippingAddress();

        $countryLabel = $address->getCountryId();
        foreach ($this->translate->getOptionCountries() as $country) {
            if ($country['value'] == $address->getCountryId()) {
                $countryLabel = $country['label'];
            }
        }

        $target = $observer->getTarget();
        $target['delivery'] = [
            'type' => $order->getShippingMethod(),
            'carrier' => [
                'name' => $order->getShippingDescription(),
            ],
            'destination' => [
                'address' => [
                    'lines' => $address->getStreet(),
                    'city' => $address->getCity(),
                    'zip_code' => $address->getPostcode(),
                    'regions' => [
                        'country' => [
                            'code' => $address->getCountryId(),
                            'name' => $countryLabel,
                        ],
                    ],
                    'contact' => array_filter(
                        $this->objectCopyService->getDataFromFieldset(
                            'onestock_address_mapping',
                            'to_onestock_contact',
                            $address
                        )
                    ),
                ],
            ],
        ];
    }
}
