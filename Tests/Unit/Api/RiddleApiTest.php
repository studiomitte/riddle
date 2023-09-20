<?php

declare(strict_types=1);

namespace StudioMitte\Riddle\Tests\Unit\Api;

/**
 * This file is part of the "riddle" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use GuzzleHttp\Client;
use StudioMitte\Riddle\Api\RiddleApi;
use StudioMitte\Riddle\ExtensionConfiguration;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\BaseTestCase;

class RiddleApiTest extends BaseTestCase
{

    private $listOfRiddles = [
        [
            'title' => 'riddle 1',
            'id' => 123,
        ],
        [
            'title' => 'riddle 2',
            'id' => 456
        ],
    ];

    /**
     * @test
     */
    public function riddleListReturnsResult(): void
    {
        $mockedRiddleApi = $this->getAccessibleMock(RiddleApi::class, ['request'], [], '', false);
        $mockedRiddleApi->expects(self::once())->method('request')->with('riddle/list')->willReturn($this->listOfRiddles);

        /** @var ExtensionConfiguration $extensionConfiguration */
        $extensionConfiguration = $this->prophesize(ExtensionConfiguration::class);
        $extensionConfiguration->getApiKey()->willReturn('abc');
        $mockedRiddleApi->_set('extensionConfiguration', $extensionConfiguration->reveal());

        $riddles = $mockedRiddleApi->getRiddleList();
        self::assertEquals($this->listOfRiddles, $riddles);
    }

    /**
     * @test
     * @dataProvider riddleIdReturnsResultDataProvider
     * @param int $id
     * @param array $expectedResult
     */
    public function riddleIdReturnsResult(int $id, array $expectedResult): void
    {
        $response = ['response' => ['items' => $this->listOfRiddles]];
        $mockedRiddleApi = $this->getAccessibleMock(RiddleApi::class, ['request'], [], '', false);
        $mockedRiddleApi->expects(self::once())->method('request')->with('riddle/list')->willReturn($response);

        $extensionConfiguration = $this->prophesize(ExtensionConfiguration::class);
        $extensionConfiguration->getApiKey()->willReturn('abc');
        $mockedRiddleApi->_set('extensionConfiguration', $extensionConfiguration->reveal());

        $riddle = $mockedRiddleApi->getRiddleItem($id);
        self::assertEquals($expectedResult, $riddle);
    }

    public function riddleIdReturnsResultDataProvider(): array
    {
        return [
            'riddle found' => [
                456, [
                    'title' => 'riddle 2',
                    'id' => 456
                ]
            ],
            'no riddle found' => [
                123456, []
            ]
        ];
    }


    /**
     * @test
     */
    public function embedCodeReturnsResult(): void
    {
        $response = ['response' => 'some html'];
        $mockedRiddleApi = $this->getAccessibleMock(RiddleApi::class, ['request'], [], '', false);
        $mockedRiddleApi->expects(self::once())->method('request')->with('riddle/embed-code/123')->willReturn($response);

        $extensionConfiguration = $this->prophesize(ExtensionConfiguration::class);
        $extensionConfiguration->getApiKey()->willReturn('abc');
        $mockedRiddleApi->_set('extensionConfiguration', $extensionConfiguration->reveal());

        self::assertEquals($response, $mockedRiddleApi->getEmbedCode(123));
    }

    /**
     * @test
     */
    public function constructorSetsExtensionConfiguration(): void
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['riddle'] = ['apiKey' => '123'];
        $configuration = new ExtensionConfiguration();

        $mockedRiddleApi = $this->getAccessibleMock(RiddleApi::class, ['request'], [], '', true);

        /** @var ExtensionConfiguration $extensionConfigurationFromApi */
        $extensionConfigurationFromApi = $mockedRiddleApi->_get('extensionConfiguration');
        self::assertEquals(123, $configuration->getApiKey());
    }

    /**
     * @test
     */
    public function requestIsProperlyBuilt(): void
    {
        $expected = ['response' => ['works' => 1]];
        $response = new Response();
        $response->getBody()->write(json_encode($expected));
        $response->getBody()->rewind();

        $client = $this->getAccessibleMock(Client::class, ['request'], [], '', true);
        $client->expects(self::once())->method('request')->willReturn($response);
        GeneralUtility::addInstance(Client::class, $client);

        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['riddle'] = ['apiKey' => '123', 'apiToken' => 456];

        $mockedRiddleApi = $this->getAccessibleMock(RiddleApi::class, ['dummy'], [], '', true);

        self::assertEquals($expected, $mockedRiddleApi->_call('request', 'action'));
    }

    /**
     * @test
     */
    public function requestErrorThrowsException(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionCode(1597956586);
        $expected = ['response' => ['works' => 1]];
        $response = new Response('php://temp', 404);
        $response->getBody()->write(json_encode($expected));
        $response->getBody()->rewind();

        $client = $this->getAccessibleMock(Client::class, ['request'], [], '', true);
        $client->expects(self::once())->method('request')->willReturn($response);
        GeneralUtility::addInstance(Client::class, $client);

        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['riddle'] = ['apiKey' => '123', 'apiToken' => 456];

        $mockedRiddleApi = $this->getAccessibleMock(RiddleApi::class, ['dummy'], [], '', true);

        self::assertEquals($expected, $mockedRiddleApi->_call('request', 'action'));

    }
}
