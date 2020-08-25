<?php

declare(strict_types=1);

namespace StudioMitte\Riddle\Tests\Unit\Backend\Element;

/**
 * This file is part of the "riddle" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Prophecy\Argument;
use StudioMitte\Riddle\Backend\Element\SelectSingleElementWithNoIcon;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\BaseTestCase;

class SelectSingleElementWithNoIconTest extends BaseTestCase
{

    /**
     * @test
     */
    public function hiddenClassIsAdded(): void
    {
        $data = [
            'tableName' => 'tt_content',
            'fieldName' => 'field',
            'databaseRow' => [
                'uid' => 123
            ],
            'parameterArray' => [
                'itemFormElName' => 'itemFormElName',
                'fieldChangeFunc' => [],
                'fieldConf' => [
                    'config' => [
                        'size' => 1,
                        'header' => 'header',
                        'items' => [
                            [
                                0 => 'label',
                                1 => '--div--',
                                'icon' => 'some-icon'],
                            [
                                0 => 'label 2',
                                1 => 'fo',
                                2 => 'another-icon']
                        ]
                    ]
                ]
            ],
            'inlineStructure' => [],
            'isInlineChild' => false,
            'inlineParentUid' => false,
            'size' => 1
        ];
        $iconFactoryProphecy = $this->prophesize(IconFactory::class);
        GeneralUtility::addInstance(IconFactory::class, $iconFactoryProphecy->reveal());
        $iconProphecy = $this->prophesize(Icon::class);
        $iconProphecy->render()->willReturn('some icon');
        $iconFactoryProphecy->getIcon(Argument::cetera())->willReturn($iconProphecy->reveal());

        /** @var SelectSingleElementWithNoIcon $mockedSelectSingleElement */
        $mockedSelectSingleElement = $this->getAccessibleMock(SelectSingleElementWithNoIcon::class, ['renderFieldInformation', 'renderFieldControl', 'renderFieldWizard'], [], '', false);
        $mockedSelectSingleElement->_set('data', $data);
        $mockedSelectSingleElement->expects(self::once())->method('renderFieldInformation')->willReturn(['html' => '']);
        $mockedSelectSingleElement->expects(self::once())->method('renderFieldControl')->willReturn(['html' => '']);
        $mockedSelectSingleElement->expects(self::once())->method('renderFieldWizard')->willReturn(['html' => '']);

        self::assertStringContainsString('input-group-addon input-group-icon hidden', $mockedSelectSingleElement->render()['html']);
    }
}
