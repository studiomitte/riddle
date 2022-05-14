<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'riddle.com Integration',
    'description' => 'Embed riddles from riddle.com',
    'category' => 'plugin',
    'author' => 'Georg Ringer',
    'author_email' => 'gr@studiomitte.com',
    'author_company' => 'studiomitte.com',
    'state' => 'beta',
    'clearCacheOnLoad' => true,
    'version' => '1.1.0',
    'constraints' =>
        [
            'depends' =>
                [
                    'typo3' => '10.4.99-11.5.99'
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
