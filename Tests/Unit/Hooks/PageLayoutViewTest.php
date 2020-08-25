<?php

declare(strict_types=1);

namespace StudioMitte\Riddle\Tests\Unit\Hooks;

/**
 * This file is part of the "riddle" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use StudioMitte\Riddle\Api\RiddleApi;
use StudioMitte\Riddle\Exception\ApiConfigurationMissingException;
use StudioMitte\Riddle\Hooks\PageLayoutView;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\TestingFramework\Core\BaseTestCase;

class PageLayoutViewTest extends BaseTestCase
{

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

    /**
     * @test
     */
    public function extensionSummaryReturnsViewContent(): void
    {
        $standaloneView = $this->getAccessibleMock(StandaloneView::class, ['assignMultiple', 'render'], [], '', false);
        $standaloneView->expects(self::once())->method('assignMultiple');
        $standaloneView->expects(self::once())->method('render')->willReturn('some html');

        $mockedPageLayoutView = $this->getAccessibleMock(PageLayoutView::class, ['getView', 'getRiddleData'], [], '', false);
        $mockedPageLayoutView->expects(self::once())->method('getView')->willReturn($standaloneView);
        $mockedPageLayoutView->expects(self::once())->method('getRiddleData')->willReturn([]);

        $params = [
            'row' => [
                'pi_flexform' => ''
            ]
        ];
        self::assertEquals('some html', $mockedPageLayoutView->getExtensionSummary($params));
    }

    /**
     * @test
     */
    public function extensionSummaryTransformsExceptionIntoVariable(): void
    {
        $standaloneView = $this->getAccessibleMock(StandaloneView::class, ['assign', 'render'], [], '', false);
        $standaloneView->expects(self::once())->method('assign')->with('apiNotConfigured', true);
        $standaloneView->expects(self::once())->method('render')->willReturn('some html');

        $mockedPageLayoutView = $this->getAccessibleMock(PageLayoutView::class, ['getView', 'getRiddleData'], [], '', false);
        $mockedPageLayoutView->expects(self::once())->method('getView')->willReturn($standaloneView);
        $mockedPageLayoutView->expects(self::once())->method('getRiddleData')->willThrowException(new ApiConfigurationMissingException());

        $params = [
            'row' => [
                'pi_flexform' => ''
            ]
        ];
        self::assertEquals('some html', $mockedPageLayoutView->getExtensionSummary($params));
    }

    /**
     * @test
     */
    public function viewIsReturned(): void
    {
        $standaloneView = $this->prophesize(StandaloneView::class);
        GeneralUtility::addInstance(StandaloneView::class, $standaloneView->reveal());

        $mockedPageLayoutView = $this->getAccessibleMock(PageLayoutView::class, ['dummy'], [], '', false);
        $view = $mockedPageLayoutView->_call('getView');
        self::assertInstanceOf(StandaloneView::class, $view);
    }
}
