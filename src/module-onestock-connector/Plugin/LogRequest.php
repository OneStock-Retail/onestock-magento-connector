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
use Smile\Onestock\Api\Data\ConfigInterface;

/**
 * Add log to every requests
 */
class LogRequest
{
    public function __construct(
        protected LoggerInterface $logger,
        protected ConfigInterface $config
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
        $uuid = uniqid();
        if ($this->config->logIsEnabled()) {
            $this->logger->debug("Url      : " . $uuid . " : " . $request->getMethod() . " " . $request->getUri());
            $this->logger->debug("Request  : " . $uuid . " : " . $request->getBody());
        }
        $response = $proceed($request, $server);
        if ($this->config->logIsEnabled()) {
            $this->logger->debug("Response : " . $uuid . " : " . $response->getBody());
            $response->getBody()->rewind();
        }
        return $response;
    }
}
