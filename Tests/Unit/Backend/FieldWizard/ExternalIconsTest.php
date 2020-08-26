<?php

declare(strict_types=1);

namespace StudioMitte\Riddle\Tests\Unit\Backend\FieldWizard;

/**
 * This file is part of the "riddle" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use StudioMitte\Riddle\Backend\FieldWizard\ExternalIcons;
use TYPO3\TestingFramework\Core\BaseTestCase;

class ExternalIconsTest extends BaseTestCase
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
                                2 => 'https://some-external.icon.com']
                        ]
                    ]
                ]
            ],
            'inlineStructure' => [],
            'isInlineChild' => false,
            'inlineParentUid' => false,
            'size' => 1
        ];

        /** @var ExternalIcons $externalIcons */
        $externalIcons = $this->getAccessibleMock(ExternalIcons::class, ['dummy'], [], '', false);
        $externalIcons->_set('data', $data);

        $result = $externalIcons->render();

        self::assertStringContainsString('<img src="https://some-external.icon.com', $result['html']);
    }
}
