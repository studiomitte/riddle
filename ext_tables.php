<?php

defined('TYPO3_MODE') or die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addModule(
    'site',
    'riddle',
    '',
    '',
    [
        'routeTarget' => \StudioMitte\Riddle\Controller\ManagementController::class . '::handleRequest',
        'access' => 'group,user',
        'name' => 'site_riddle',
        'icon' => 'EXT:riddle/Resources/Public/Icons/Extension.png',
        'labels' => 'LLL:EXT:riddle/Resources/Private/Language/locallang.xlf'
    ]
);
