<?php

defined('TYPO3') or die();

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1598116730] = [
    'nodeName' => 'ExternalIcons',
    'priority' => 70,
    'class' => \StudioMitte\Riddle\Backend\FieldWizard\ExternalIcons::class,
];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1598116731] = [
    'nodeName' => 'selectSingleWithNoIconPrefix',
    'priority' => 70,
    'class' => \StudioMitte\Riddle\Backend\Element\SelectSingleElementWithNoIcon::class,
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript(
    'riddle',
    'setup',
    '
tt_content.riddle_riddle =< lib.contentElement
tt_content.riddle_riddle {
    templateName = Generic
    20 = USER
    20.userFunc = StudioMitte\Riddle\Controller\PluginController->run
}
',
    'defaultContentRendering'
);
