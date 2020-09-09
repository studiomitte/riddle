<?php

declare(strict_types=1);

namespace StudioMitte\Riddle\Tests\Unit\Plugin;

/**
 * This file is part of the "riddle" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Prophecy\Argument;
use StudioMitte\Riddle\Api\RiddleApi;
use StudioMitte\Riddle\Plugin\PluginController;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\PhpFrontend;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\TestingFramework\Core\BaseTestCase;

class RiddlePluginTest extends BaseTestCase
{

    /**
     * @test
     * @dataProvider htmlCodeIsDeliveredDataProvider
     */
    public function htmlCodeIsDelivered(int $id, string $html): void
    {
        $cacheFrontendProphecy = $this->prophesize(PhpFrontend::class);
        $cacheFrontendProphecy->require(Argument::any())->willReturn(false);
        $cacheFrontendProphecy->get(Argument::any())->willReturn([]);
        $cacheFrontendProphecy->set(Argument::any(), Argument::any())->willReturn([]);
        $cacheManagerProphecy = $this->prophesize(CacheManager::class);
        $cacheManagerProphecy->getCache(Argument::any())->willReturn($cacheFrontendProphecy->reveal());
        GeneralUtility::setSingletonInstance(CacheManager::class, $cacheManagerProphecy->reveal());

        $mockedContentObjectRenderer = $this->getAccessibleMock(ContentObjectRenderer::class, ['dummy'], [], '', false);
        $mockedContentObjectRenderer->_set('data', [
            'uid' => 123,
            'pi_flexform' => '<?xml version="1.0" encoding="utf-8" standalone="yes" ?>
<T3FlexForms>
    <data>
        <sheet index="sDEF">
            <language index="lDEF">
                <field index="riddle">
                    <value index="vDEF">' . $id . '</value>
                </field>
            </language>
        </sheet>
    </data>
</T3FlexForms>'
        ]);
        $mockedProvider = $this->getAccessibleMock(PluginController::class, ['getRiddleHtml'], [], '', false);
        $mockedProvider->_set('cObj', $mockedContentObjectRenderer);
        if ($html) {
            $mockedProvider->expects(self::once())->method('getRiddleHtml')->with($id)->willReturn($html);
        } else {
            $mockedProvider->expects(self::never())->method('getRiddleHtml')->with($id)->willReturn($html);
        }
        self::assertEquals($html, $mockedProvider->run('', []));
    }

    public function htmlCodeIsDeliveredDataProvider(): array
    {
        return [
            'working flexforms' => [
                456, 'some html string'
            ],
            'non working flexforms' => [
                0, ''
            ]
        ];
    }

    /**
     * @test
     */
    public function properApiCallIsUsed()
    {
        /** @var RiddleApi $riddleApiProphecy */
        $riddleApiProphecy = $this->prophesize(RiddleApi::class);
        $response = ['response' => 'some html'];
        $riddleApiProphecy->getEmbedCode(123)->willReturn($response);
        GeneralUtility::addInstance(RiddleApi::class, $riddleApiProphecy->reveal());

        $mockedRiddleApi = $this->getAccessibleMock(PluginController::class, ['dummy'], [], '', false);
        self::assertEquals('some html', $mockedRiddleApi->_call('getRiddleHtml', 123));
    }
}
