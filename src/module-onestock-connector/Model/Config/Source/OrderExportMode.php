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

namespace Smile\Onestock\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade to newer
 * versions in the future.
 *
 * @copyright 2023 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
class OrderExportMode implements ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        return array_map(
            function ($key, $value) {
                return ['value' => $key, 'label' => $value];
            },
            array_keys($this->toArray()),
            array_values($this->toArray())
        );
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'sales_model_service_quote_submit_success' => __('Immediately when order is placed'),
            'sales_order_invoice_pay' => __('Wait for invoice paid'),
        ];
    }
}
