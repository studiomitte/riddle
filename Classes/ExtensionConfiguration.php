<?php

declare(strict_types=1);

namespace StudioMitte\Riddle;

/**
 * This file is part of the "riddle" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use StudioMitte\Riddle\Exception\ApiConfigurationMissingException;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ExtensionConfiguration implements SingletonInterface
{

    /** @var string */
    protected $apiKey = '';

    /** @var string */
    protected $apiToken = '';

    public function __construct()
    {
        try {
            $configuration = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)->get('riddle');

            if (is_array($configuration)) {
                $this->apiKey = (string)($configuration['apiKey'] ?? '');
                $this->apiToken = (string)($configuration['apiToken'] ?? '');
            }
        } catch (\Exception $e) {
        }

        if (!$this->apiToken && !$this->apiKey) {
            throw new ApiConfigurationMissingException('API not configured', 1598035744);
        }
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @return string
     */
    public function getApiToken(): string
    {
        return $this->apiToken;
    }
}
