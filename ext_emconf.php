<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'riddle.com Integration',
    'description' => 'Embed riddles from riddle.com',
    'category' => 'plugin',
    'author' => 'Georg Ringer',
    'author_email' => 'gr@studiomitte.com',
    'author_company' => 'studiomitte.com',
    'state' => 'stable',
    'version' => '2.0.0',
    'constraints' =>
        [
            'depends' =>
                [
                    'typo3' => '11.5.0-12.4.99'
                ],
        ],
    'autoload' =>
        [
            'psr-4' =>
                [
                    'StudioMitte\\Riddle\\' => 'Classes',
                ],
        ]
];
