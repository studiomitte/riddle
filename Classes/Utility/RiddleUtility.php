<?php

declare(strict_types=1);

/*
 * This file is part of the "riddle" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace StudioMitte\Riddle\Utility;

use TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class RiddleUtility
{
    /**
     * Enrich the API riddle response
     */
    public static function enrichRiddleData(array $item): array
    {
        if (isset($item['created']) && is_array($item['created'])) {
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', (string)($item['created']['at'] ?? ''));
            if ($date) {
                $item['_enriched']['dateCreated'] = $date->getTimestamp();
            }
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', (string)($item['modified']['at'] ?? ''));
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
     */
    public static function getRiddleId(string $flexforms): string
    {
        if ($flexforms === '') {
            return '';
        }
        $data = GeneralUtility::makeInstance(FlexFormTools::class)->convertFlexFormContentToArray($flexforms);
        return (string)($data['riddle'] ?? '');
    }
}
