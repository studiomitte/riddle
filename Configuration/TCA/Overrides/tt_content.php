<?php

defined('TYPO3') or die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
    [
        'label' => 'LLL:EXT:riddle/Resources/Private/Language/locallang.xlf:plugin.title',
        'description' => 'LLL:EXT:riddle/Resources/Private/Language/locallang.xlf:plugin.description',
        'value' => 'riddle_riddle',
        'icon' => 'ext-riddle-plugin',
        'group' => 'plugins',
    ],
    'FILE:EXT:riddle/Configuration/FlexForms/flexform_riddle.xml'
);
