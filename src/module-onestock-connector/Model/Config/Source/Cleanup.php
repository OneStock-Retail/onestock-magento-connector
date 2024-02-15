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
 * @copyright 2024 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
class Cleanup implements ArrayInterface
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
            'nothing' => __('Do Nothing'),
            'remove' => __('Remove'),
            'archive' => __('Archive'),
        ];
    }
}
