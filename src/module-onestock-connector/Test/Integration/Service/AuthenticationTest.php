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

namespace Smile\Onestock\Test\Service;

use Exception;
use Magento\Framework\App\Bootstrap;
use Magento\Framework\App\Http;
use Magento\Framework\App\ObjectManager;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Smile\Onestock\Api\Data\ConfigInterface;
use Smile\Onestock\Model\Authentication\Credential;
use Smile\Onestock\Service\Onestock\Authentication;

/**
 * Test service to login
 *
 * @author   Pascal Noisette <pascal.noisette@smile.fr>
 */
class AuthenticationTest extends TestCase
{
    /**
     * Object to test
     */
    protected mixed $authentication;

    /**
     * Instanciate object to test
     *
     * @throws RuntimeException
     */
    public function setUp(): void
    {
        parent::setUp();

        try {
            Bootstrap::create(BP, $_SERVER)->createApplication(Http::class);
            $this->authentication = ObjectManager::getInstance()->create(Authentication::class);
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage(), 0, $e);
        }
    }

    /**
     * Validate you are able to login againt api in integration
     *
     * @param  \Smile\Onestock\Api\Data\Authentication\Credential $credential
     * @throw \GuzzleHttp\Exception\RequestException
     */
    public function testLogin(): void
    {
        /** @var \Smile\Onestock\Model\Config */
        $config = $this->getMockBuilder(ConfigInterface::class)
            ->setMethods(['getHost', 'getCredentials', 'getOptions'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $config->method('getHost')->willReturn($_ENV["HOST"]);
        $config->method('getOptions')->willReturn([]);
        $config->method('getCredentials')
               ->willReturn(
                   [
                   "user_id" => $_ENV["USER_ID"],
                   "password" => $_ENV["PASSWORD"],
                   "site_id" => $_ENV["SITE_ID"],
                   ]
               );

        /**@var \Smile\Onestock\Model\Authentication\Credential */
        $credential = new Credential($config->getCredentials());

        /** @var \Smile\Onestock\Api\Data\Authentication\Token */
        $token = $this->authentication->login($config, $credential);

        $this->assertNotEmpty($token->getToken());
    }
}
