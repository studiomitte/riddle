<?php

return [
    'frontend' => [
        'studiomitte/riddle/router' => [
            'target' => \StudioMitte\Riddle\Middleware\WebhookEndpoint::class,
            'after' => [
                'typo3/cms-frontend/site'
            ],
            'before' => [
                'typo3/cms-frontend/static-route-resolver'
            ]
        ],
    ],
];
