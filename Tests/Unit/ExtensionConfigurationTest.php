<?php

declare(strict_types=1);

namespace StudioMitte\Riddle\Tests\Unit;

/**
 * This file is part of the "riddle" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Prophecy\PhpUnit\ProphecyTrait;
use StudioMitte\Riddle\Exception\ApiConfigurationMissingException;
use StudioMitte\Riddle\ExtensionConfiguration;
use TYPO3\TestingFramework\Core\BaseTestCase;

class ExtensionConfigurationTest extends BaseTestCase
{

    use ProphecyTrait;

    /**
     * @test
     * @dataProvider configurationCanBeRetrievedDataProvider
     * @param array $configuration
     * @param mixed $expectedKey
     * @param mixed $expectedToken
     */
    public function configurationCanBeRetrieved(array $configuration, $expectedKey, $expectedToken): void
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['riddle'] = $configuration;

        $configuration = new ExtensionConfiguration();

        self::assertEquals($expectedKey, $configuration->getApiKey());
        self::assertEquals($expectedToken, $configuration->getApiToken());
    }

    public function configurationCanBeRetrievedDataProvider(): array
    {
        return [
            'working api' => [
                ['apiKey' => '123', 'apiToken' => 456], 123, 456
            ]
        ];
    }

    /**
     * @test
     */
    public function emptyConfigurationThrowsException(): void
    {
        $this->expectException(ApiConfigurationMissingException::class);
        $this->expectExceptionCode(1598035744);

        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['riddle'] = [];
        new ExtensionConfiguration();

    }
}
