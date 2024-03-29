<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'riddle.com Integration',
    'description' => 'Embed riddles from riddle.com',
    'category' => 'plugin',
    'author' => 'Georg Ringer',
    'author_email' => 'gr@studiomitte.com',
    'author_company' => 'studiomitte.com',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'version' => '1.2.0',
    'constraints' =>
        [
            'depends' =>
                [
                    'typo3' => '9.5.0-11.5.99'
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
