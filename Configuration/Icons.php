<?php

use TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider;

return [
    'ext-riddle-plugin' => [
        'provider' => BitmapIconProvider::class,
        'source' => 'EXT:riddle/Resources/Public/Icons/Plugin.png',
    ],
    'ext-riddle-module' => [
        'provider' => BitmapIconProvider::class,
        'source' => 'EXT:riddle/Resources/Public/Icons/Extension.png',
    ],
];
