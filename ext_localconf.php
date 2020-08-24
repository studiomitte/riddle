<?php

call_user_func(static function () {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
        <INCLUDE_TYPOSCRIPT: source="FILE:EXT:riddle/Configuration/TSconfig/ContentElementWizard.typoscript">
    ');

    if (TYPO3_MODE === 'BE') {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1598116730] = [
            'nodeName' => 'ExternalIcons',
            'priority' => 70,
            'class' => \StudioMitte\Riddle\Backend\FieldWizard\ExternalIcons::class
        ];
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1598116731] = [
            'nodeName' => 'selectSingleWithNoIconPrefix',
            'priority' => 70,
            'class' => \StudioMitte\Riddle\Backend\Element\SelectSingleElementWithNoIcon::class
        ];

        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
        $iconRegistry->registerIcon(
            'ext-riddle-plugin',
            \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            ['source' => 'EXT:riddle/Resources/Public/Icons/Plugin.svg']
        );

        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info']['riddle_riddle']['riddle'] =
            \StudioMitte\Riddle\Hooks\PageLayoutView::class . '->getExtensionSummary';
    }
});
