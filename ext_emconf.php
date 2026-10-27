<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'riddle.com Integration',
    'description' => 'Embed riddles from riddle.com',
    'category' => 'plugin',
    'author' => 'Georg Ringer',
    'author_email' => 'gr@studiomitte.com',
    'author_company' => 'studiomitte.com',
    'state' => 'stable',
    'version' => '3.0.0',
    'constraints' =>
        [
            'depends' =>
                [
                    'php' => '8.2.0-8.5.99',
                    'typo3' => '14.0.0-14.4.99',
                ],
        ],
    'autoload' =>
        [
            'psr-4' =>
                [
                    'StudioMitte\\Riddle\\' => 'Classes',
                ],
        ],
];
