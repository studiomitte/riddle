<?php

$config = \TYPO3\CodingStandards\CsFixerConfig::create();
$config->setHeader(
    'This file is part of the "riddle" Extension for TYPO3 CMS.

For the full copyright and license information, please read the
LICENSE.txt file that was distributed with this source code.',
    true
);
$config->getFinder()
    ->in(realpath(__DIR__ . '/../../'))
    ->ignoreVCSIgnored(true)
    ->notPath('/^.Build\//')
    ->notPath('/^Build\/php-cs-fixer\/php-cs-fixer.php/')
    ->notPath('/^Configuration\//')
    ->notPath('/^Documentation\//')
    ->notName('/^ext_(emconf|localconf|tables).php/');

return $config;
