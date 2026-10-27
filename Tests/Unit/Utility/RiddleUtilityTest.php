<?php

declare(strict_types=1);

/*
 * This file is part of the "riddle" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace StudioMitte\Riddle\Tests\Unit\Utility;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use StudioMitte\Riddle\Utility\RiddleUtility;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

final class RiddleUtilityTest extends UnitTestCase
{
    public static function apiDataIsEnrichedDataProvider(): array
    {
        return [
            'v3 api dates are transformed' => [
                [
                    'created' => ['at' => '2020-08-21 19:21:07'],
                    'modified' => ['at' => '2020-09-21 19:21:07'],
                ],
                [
                    'created' => ['at' => '2020-08-21 19:21:07'],
                    'modified' => ['at' => '2020-09-21 19:21:07'],
                    '_enriched' => [
                        'dateCreated' => 1598030467,
                        'datepublished' => 1600708867,
                    ],
                ],
            ],
            'legacy dates are transformed' => [
                [
                    'dateCreated' => '2020-08-21 19:21:07',
                    'datepublished' => '2020-09-21 19:21:07',
                ],
                [
                    'dateCreated' => '2020-08-21 19:21:07',
                    'datepublished' => '2020-09-21 19:21:07',
                    '_enriched' => [
                        'dateCreated' => 1598030467,
                        'datepublished' => 1600708867,
                    ],
                ],
            ],
            'invalid dates given' => [
                [
                    'dateCreated' => '08-21 19:21:07',
                    'datepublished' => '09-21 19:21:07',
                ],
                [
                    'dateCreated' => '08-21 19:21:07',
                    'datepublished' => '09-21 19:21:07',
                ],
            ],
        ];
    }

    #[Test]
    #[DataProvider('apiDataIsEnrichedDataProvider')]
    public function apiDataIsEnriched(array $given, array $expected): void
    {
        $timezone = date_default_timezone_get();
        date_default_timezone_set('Europe/Berlin');
        try {
            self::assertEquals($expected, RiddleUtility::enrichRiddleData($given));
        } finally {
            date_default_timezone_set($timezone);
        }
    }

    #[Test]
    public function emptyFlexFormReturnsEmptyId(): void
    {
        self::assertSame('', RiddleUtility::getRiddleId(''));
    }
}
