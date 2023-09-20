<?php

declare(strict_types=1);

namespace StudioMitte\Riddle\Tests\Unit\Hooks;

/**
 * This file is part of the "riddle" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use PHPUnit\Framework\MockObject\MockObject;
use StudioMitte\Riddle\Api\RiddleApi;
use StudioMitte\Riddle\Hooks\ItemsProcFunc;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\BaseTestCase;

class ItemsProcFuncTest extends BaseTestCase
{
    protected $allRiddles = [
        [
            'id' => 123,
            'title' => 'A riddle',
            'dateCreated' => '2020-08-21 19:21:07',
            'datepublished' => '2020-09-21 19:21:07',
            'thumb' => 'https://cdn.riddle.com/img1.png',
        ],
        [
            'id' => 456,
            'title' => 'Another riddle',
            'type' => 'Quiz',
            'dateCreated' => '2019-08-21 19:21:07',
            'datepublished' => '2019-09-21 19:21:07',
        ],
    ];

    /**
     * @test
     */
    public function selectListIsFilled(): void
    {
        /** @var MockObject|AccessibleObjectInterface|ItemsProcFunc $mockedProvider */
        $mockedProvider = $this->getAccessibleMock(ItemsProcFunc::class, ['getAllRiddles'], [], '', false);
        $mockedProvider->expects(self::once())->method('getAllRiddles')->willReturn($this->allRiddles);

        $config = [];
        $mockedProvider->riddleList($config);
        $expected = [
            ['A riddle [-] - 21-09-20', 123, 'https://cdn.riddle.com/img1.png'],
            ['Another riddle [Quiz] - 21-09-19', 456, ''],
        ];
        self::assertEquals($expected, $config['items']);
    }

    /**
     * @test
     */
    public function allRiddlesAreReturned(): void
    {
        $allRiddles = [
            'response' => [
                'items' => [
                    ['title' => 'riddle 1'],
                    ['title' => 'riddle 2'],
                ],
            ],
        ];
        /** @var RiddleApi $riddleApiProphecy */
        $riddleApiProphecy = $this->prophesize(RiddleApi::class);
        $riddleApiProphecy->getRiddleList()->willReturn($allRiddles);
        GeneralUtility::addInstance(RiddleApi::class, $riddleApiProphecy->reveal());

        $mockedRiddleApi = $this->getAccessibleMock(ItemsProcFunc::class, ['dummy'], [], '', false);
        self::assertEquals($allRiddles['response']['items'], $mockedRiddleApi->_call('getAllRiddles'));
    }
}
