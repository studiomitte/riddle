<?php

declare(strict_types=1);

/*
 * This file is part of the "riddle" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace StudioMitte\Riddle\Tests\Unit\Domain;

use PHPUnit\Framework\Attributes\Test;
use StudioMitte\Riddle\Domain\LogItem;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

final class LogItemTest extends UnitTestCase
{
    #[Test]
    public function webhookPayloadIsConverted(): void
    {
        $data = [
            'riddle' => ['id' => 42, 'title' => 'Quiz', 'type' => 'quiz'],
            'timeTaken' => ['milliseconds' => 1500],
            'resultData' => ['scorePercentage' => 80, 'title' => 'Great'],
            'lead2' => ['Name' => ['value' => 'Jane'], 'Email' => ['value' => 'jane@example.org']],
        ];

        $result = LogItem::convert(['riddleId' => 42, 'data' => $data]);

        self::assertSame(42, $result['riddle_id']);
        self::assertSame('Quiz', $result['riddle_title']);
        self::assertSame(1500, $result['time_taken']);
        self::assertSame(80, $result['result_score_percentage']);
        self::assertSame('Jane', $result['lead_name']);
        self::assertSame('jane@example.org', $result['lead_email']);
        self::assertSame(json_encode($data), $result['full_json']);
    }

    #[Test]
    public function missingDataFallsBackToDefaults(): void
    {
        $result = LogItem::convert(['riddleId' => 42]);

        self::assertSame(0, $result['riddle_id']);
        self::assertSame('', $result['lead_email']);
    }
}
