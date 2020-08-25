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
use Prophecy\PhpUnit\ProphecyTrait;
use StudioMitte\Riddle\Plugin\RiddlePlugin;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\PhpFrontend;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\TestingFramework\Core\BaseTestCase;

class RiddlePluginTest extends BaseTestCase
{
//    use ProphecyTrait;

    /**
     * @test
     */
    public function htmlCodeIsDelivered(): void
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
                    <value index="vDEF">456</value>
                </field>
            </language>
        </sheet>
    </data>
</T3FlexForms>'
        ]);
        $mockedProvider = $this->getAccessibleMock(RiddlePlugin::class, ['getRiddleHtml'], [], '', false);
        $mockedProvider->_set('cObj', $mockedContentObjectRenderer);
        $mockedProvider->expects(self::once())->method('getRiddleHtml')->willReturn('some html string');

        self::assertEquals('some html string', $mockedProvider->run('', []));
    }
}
