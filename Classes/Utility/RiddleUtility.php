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
     * @return int
     */
    public static function getRiddleId(string $flexforms): int
    {
        if (!$flexforms) {
            return 0;
        }
        $data = GeneralUtility::makeInstance(FlexFormService::class)->convertFlexFormContentToArray($flexforms);
        return (int)($data['riddle'] ?? 0);
    }
}
