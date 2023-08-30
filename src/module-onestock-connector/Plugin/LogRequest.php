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

namespace Smile\Onestock\Plugin;

use Psr\Log\LoggerInterface;
use Smile\Onestock\Helper\Config as OnestockConfig;

/**
 * Add log to every requests
 */
class LogRequest
{
    public function __construct(
        protected LoggerInterface $logger,
        protected OnestockConfig $config,
    ) {
    }

    /**
     * Log if enabled
     */
    public function aroundSend(
        mixed $subject,
        mixed $proceed,
        mixed $request,
        mixed $server
    ): mixed {
        if ($this->config->logIsEnabled()) {
            $this->logger->debug($request->getBody());
        }
        $response = $proceed($request, $server);
        if ($this->config->logIsEnabled()) {
            $this->logger->debug($response->getBody());
        }
        return $response;
    }
}
