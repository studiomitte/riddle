<?php

declare(strict_types=1);

namespace StudioMitte\Riddle;

/**
 * This file is part of the "riddle" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ExtensionConfiguration implements SingletonInterface
{

    protected string $apiKey = '';

    public function __construct()
    {
        try {
            $configuration = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)->get('riddle');

            if (is_array($configuration)) {
                $this->apiKey = (string)($configuration['apiKey'] ?? '');
            }
        } catch (\Exception $e) {
        }
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

}
