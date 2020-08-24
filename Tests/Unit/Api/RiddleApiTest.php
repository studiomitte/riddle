<?php

declare(strict_types=1);

namespace StudioMitte\Riddle\Tests\Unit\Api;

/**
 * This file is part of the "riddle" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Prophecy\PhpUnit\ProphecyTrait;
use StudioMitte\Riddle\Api\RiddleApi;
use StudioMitte\Riddle\ExtensionConfiguration;
use TYPO3\TestingFramework\Core\BaseTestCase;

class RiddleApiTest extends BaseTestCase
{
    use ProphecyTrait;

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
        $mockedRiddleApi->expects(self::once())->method('request')->with('riddle/get/list')->willReturn($this->listOfRiddles);

        /** @var ExtensionConfiguration $extensionConfiguration */
        $extensionConfiguration = $this->prophesize(ExtensionConfiguration::class);
        $extensionConfiguration->getApiKey()->willReturn('abc');
        $extensionConfiguration->getApiToken()->willReturn('def');
        $mockedRiddleApi->_set('extensionConfiguration', $extensionConfiguration->reveal());

        $riddles = $mockedRiddleApi->getRiddleList();
        self::assertEquals($this->listOfRiddles, $riddles);
    }

    /**
     * @test
     */
    public function riddleIdReturnsResult(): void
    {
        $response = ['response' => ['items' => $this->listOfRiddles]];
        $mockedRiddleApi = $this->getAccessibleMock(RiddleApi::class, ['request'], [], '', false);
        $mockedRiddleApi->expects(self::once())->method('request')->with('riddle/get/list')->willReturn($response);

        $extensionConfiguration = $this->prophesize(ExtensionConfiguration::class);
        $extensionConfiguration->getApiKey()->willReturn('abc');
        $extensionConfiguration->getApiToken()->willReturn('def');
        $mockedRiddleApi->_set('extensionConfiguration', $extensionConfiguration->reveal());

        $riddle = $mockedRiddleApi->getRiddleItem(456);
        self::assertEquals([
            'title' => 'riddle 2',
            'id' => 456
        ], $riddle);
    }

    /**
     * @test
     */
    public function embedCodeReturnsResult(): void
    {
        $response = ['response' => 'some html'];
        $mockedRiddleApi = $this->getAccessibleMock(RiddleApi::class, ['request'], [], '', false);
        $mockedRiddleApi->expects(self::once())->method('request')->with('riddle/get/embed-code?riddleId=123')->willReturn($response);

        $extensionConfiguration = $this->prophesize(ExtensionConfiguration::class);
        $extensionConfiguration->getApiKey()->willReturn('abc');
        $extensionConfiguration->getApiToken()->willReturn('def');
        $mockedRiddleApi->_set('extensionConfiguration', $extensionConfiguration->reveal());

        self::assertEquals($response, $mockedRiddleApi->getEmbedCode(123));
    }

}
