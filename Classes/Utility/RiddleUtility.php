<?php

declare(strict_types=1);

namespace StudioMitte\Riddle\Utility;

/**
 * This file is part of the "riddle" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class RiddleUtility
{

    /**
     * Enrich the API riddle response
     *
     * @param array $item
     * @return array
     */
    public static function enrichRiddleData(array $item): array
    {
        if (isset($item['created']) && is_array($item['created'])) {
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $item['created']['at']);
            if ($date) {
                $item['_enriched']['dateCreated'] = $date->getTimestamp();
            }
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $item['modified']['at']);
            if ($date) {
                $item['_enriched']['datepublished'] = $date->getTimestamp();
            }
            return $item;
        }

        foreach (['dateCreated', 'datepublished'] as $dateField) {
            if (isset($item[$dateField])) {
                $date = \DateTime::createFromFormat('Y-m-d H:i:s', $item[$dateField]);
                if ($date) {
                    $item['_enriched'][$dateField] = $date->getTimestamp();
                }
            }
        }

        return $item;
    }

    /**
     * Get riddle ID from given flexform XML
     *
     * @param string $flexforms
     * @return int|string
     */
    public static function getRiddleId(string $flexforms)
    {
        if (!$flexforms) {
            return '';
        }
        $data = GeneralUtility::makeInstance(FlexFormService::class)->convertFlexFormContentToArray($flexforms);
        return ($data['riddle'] ?? '');
    }
}
