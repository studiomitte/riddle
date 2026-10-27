<?php

use StudioMitte\Riddle\Controller\ManagementController;

return [
    'site_riddle' => [
        'parent' => 'site',
        'access' => 'user',
        'path' => '/module/site/riddle',
        'iconIdentifier' => 'ext-riddle-module',
        'labels' => 'LLL:EXT:riddle/Resources/Private/Language/locallang.xlf',
        'routes' => [
            '_default' => [
                'target' => ManagementController::class . '::handleRequest',
            ],
        ],
    ],
];
