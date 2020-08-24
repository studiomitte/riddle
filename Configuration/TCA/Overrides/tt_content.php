<?php

call_user_func(static function () {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'riddle',
        'riddle',
        'LLL:EXT:riddle/Resources/Private/Language/locallang.xlf:plugin.title',
        'ext-riddle-plugin'
    );

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['riddle_riddle'] = 'recursive,select_key,pages';
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['riddle_riddle'] = 'pi_flexform';
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
        'riddle_riddle',
        'FILE:EXT:riddle/Configuration/FlexForms/flexform_riddle.xml'
    );
});
