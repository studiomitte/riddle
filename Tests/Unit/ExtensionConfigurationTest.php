<?php

declare(strict_types=1);

namespace StudioMitte\Riddle\Tests\Unit;

/**
 * This file is part of the "riddle" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use StudioMitte\Riddle\Exception\ApiConfigurationMissingException;
use StudioMitte\Riddle\ExtensionConfiguration;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration as ExtensionConfigurationCore;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\BaseTestCase;

class ExtensionConfigurationTest extends BaseTestCase
{
    /**
     * @test
     * @dataProvider configurationCanBeRetrievedDataProvider
     * @param array $configuration
     * @param mixed $expectedKey
     * @param mixed $expectedToken
     */
    public function configurationCanBeRetrieved(array $configuration, $expectedKey): void
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['riddle'] = $configuration;

        $configuration = new ExtensionConfiguration();

        self::assertEquals($expectedKey, $configuration->getApiKey());
    }

    public function configurationCanBeRetrievedDataProvider(): array
    {
        return [
            'working api' => [
                ['apiKey' => '123'], 123,
            ],
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

    /**
     * @test
     */
    public function exceptionsAreHandledInConstructor(): void
    {
        $mockedCoreExtensionConfiguration = $this->getAccessibleMock(ExtensionConfigurationCore::class, ['get'], [], '', false);
        $mockedCoreExtensionConfiguration->expects(self::once())->method('get')->with('riddle')->willThrowException(new \RuntimeException('faked exception'));
        GeneralUtility::addInstance(ExtensionConfigurationCore::class, $mockedCoreExtensionConfiguration);

        $this->expectException(ApiConfigurationMissingException::class);
        $this->expectExceptionCode(1598035744);

        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['riddle'] = [];
        new ExtensionConfiguration();
    }
}
