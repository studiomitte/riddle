<?php

declare(strict_types=1);

namespace StudioMitte\Riddle\Tests\Unit\Hooks;

/**
 * This file is part of the "riddle" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Prophecy\PhpUnit\ProphecyTrait;
use StudioMitte\Riddle\Api\RiddleApi;
use StudioMitte\Riddle\Hooks\PageLayoutView;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\BaseTestCase;

class PageLayoutViewTest extends BaseTestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function riddleApiCallReturnsData(): void
    {
        $riddle = ['id' => 123, 'type' => 'demo', 'title' => 'a demo'];

        /** @var RiddleApi $riddleApiProphecy */
        $riddleApiProphecy = $this->prophesize(RiddleApi::class);
        $riddleApiProphecy->getRiddleItem(123)->willReturn($riddle);
        GeneralUtility::addInstance(RiddleApi::class, $riddleApiProphecy->reveal());

        $mockedPageLayoutView = $this->getAccessibleMock(PageLayoutView::class, ['dummy'], [], '', false);

        self::assertEquals($riddle, $mockedPageLayoutView->_call('getRiddleData', 123));
    }
}
