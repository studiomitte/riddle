<?php

declare(strict_types=1);

/*
 * This file is part of the "riddle" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace StudioMitte\Riddle\Tests\Unit\Hooks;

use PHPUnit\Framework\Attributes\Test;
use StudioMitte\Riddle\Hooks\ItemsProcFunc;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

final class ItemsProcFuncTest extends UnitTestCase
{
    #[Test]
    public function selectListIsFilled(): void
    {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy'] = 'd-m-y';
        $subject = new class () extends ItemsProcFunc {
            protected function getAllRiddles(): array
            {
                return [
                    [
                        'UUID' => 'abc',
                        'title' => 'A riddle',
                        'image' => 'https://cdn.riddle.com/img1.png',
                        'created' => ['at' => '2020-08-21 19:21:07'],
                        'modified' => ['at' => '2020-09-21 19:21:07'],
                    ],
                    [
                        'UUID' => 'def',
                        'title' => 'Another riddle',
                        'type' => 'Quiz',
                    ],
                ];
            }
        };

        $config = ['items' => [['label' => '', 'value' => '']]];
        $subject->riddleList($config);

        self::assertSame(
            [
                ['label' => '', 'value' => ''],
                ['label' => 'A riddle [-] - 21-09-20', 'value' => 'abc', 'icon' => 'https://cdn.riddle.com/img1.png'],
                ['label' => 'Another riddle [Quiz] - ', 'value' => 'def', 'icon' => ''],
            ],
            $config['items']
        );
    }
}
