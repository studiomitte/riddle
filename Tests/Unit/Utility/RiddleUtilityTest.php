<?php

declare(strict_types=1);

namespace StudioMitte\Riddle\Tests\Unit\Utility;

/**
 * This file is part of the "riddle" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use StudioMitte\Riddle\Utility\RiddleUtility;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\PhpFrontend;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\BaseTestCase;

class RiddleUtilityTest extends BaseTestCase
{

    use ProphecyTrait;

    /**
     * @test
     * @dataProvider apiDataIsEnrichedDataProvider
     * @param array $given
     * @param array $expected
     */
    public function apiDataIsEnriched(array $given, array $expected): void
    {
        self::assertEquals($expected, RiddleUtility::enrichRiddleData($given));
    }

    public function apiDataIsEnrichedDataProvider(): array
    {
        return [
            'both dates are transformed' => [
                [
                    'dateCreated' => '2020-08-21 19:21:07',
                    'datepublished' => '2020-09-21 19:21:07',
                ],
                [
                    'dateCreated' => '2020-08-21 19:21:07',
                    'datepublished' => '2020-09-21 19:21:07',
                    '_enriched' => [
                        'dateCreated' => 1598037667,
                        'datepublished' => 1600716067,
                    ]
                ]
            ],
            'invalid dates given' => [
                [
                    'dateCreated' => '08-21 19:21:07',
                    'datepublished' => '09-21 19:21:07',
                ],
                [
                    'dateCreated' => '08-21 19:21:07',
                    'datepublished' => '09-21 19:21:07',
                ]
            ]
        ];
    }

    /**
     * @test
     * @dataProvider idCanBeRetrievedFromFlexformsDataProvider
     * @param int $expectedId
     * @param string $flexforms
     */
    public function idCanBeRetrievedFromFlexforms(int $expectedId, string $flexforms): void
    {
        $cacheFrontendProphecy = $this->prophesize(PhpFrontend::class);
        $cacheFrontendProphecy->require(Argument::any())->willReturn(false);
        $cacheFrontendProphecy->get(Argument::any())->willReturn([]);
        $cacheFrontendProphecy->set(Argument::any(), Argument::any())->willReturn([]);
        $cacheManagerProphecy = $this->prophesize(CacheManager::class);
        $cacheManagerProphecy->getCache(Argument::any())->willReturn($cacheFrontendProphecy->reveal());
        GeneralUtility::setSingletonInstance(CacheManager::class, $cacheManagerProphecy->reveal());

        self::assertEquals($expectedId, RiddleUtility::getRiddleId($flexforms));
    }

    public function idCanBeRetrievedFromFlexformsDataProvider(): array
    {
        return [
            'working flexform' => [
                264528,
                '<?xml version="1.0" encoding="utf-8" standalone="yes" ?>
<T3FlexForms>
    <data>
        <sheet index="sDEF">
            <language index="lDEF">
                <field index="riddle">
                    <value index="vDEF">264528</value>
                </field>
            </language>
        </sheet>
    </data>
</T3FlexForms>'
            ],
            'non working flexform' => [
                0,
                '<?xml version="1.0" encoding="utf-8" standalone="yes" ?>
<T3FlexForms>
    <data>
        <sheet index="sDEF">
            <language index="lDEF">
                <field index="fo">
                    <value index="vDEF">bar</value>
                </field>
            </language>
        </sheet>
    </data>
</T3FlexForms>'
            ],
            'empty flexform' => [
                0,
                ''
            ]
        ];
    }
}
