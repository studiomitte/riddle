<?php

declare(strict_types=1);

namespace StudioMitte\Riddle\Hooks;

/**
 * This file is part of the "riddle" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use StudioMitte\Riddle\Api\RiddleApi;
use StudioMitte\Riddle\Utility\RiddleUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Render list of all riddles
 */
class ItemsProcFunc
{
    public function riddleList(array &$config): void
    {
        try {
            $riddles = $this->getAllRiddles();
            foreach ($riddles as $item) {
                $config['items'][] = $this->getSelectItem($item, false);
            }
            // @codeCoverageIgnoreStart
        } catch (\Exception $e) {
            $message = GeneralUtility::makeInstance(
                FlashMessage::class,
                $e->getMessage(),
                '',
                AbstractMessage::ERROR
            );
            GeneralUtility::makeInstance(FlashMessageService::class)
                ->getMessageQueueByIdentifier()
                ->enqueue($message);
        }
        // @codeCoverageIgnoreEnd
    }

    protected function getSelectItem(array $item, bool $isV2 = true): array
    {
        $item = RiddleUtility::enrichRiddleData($item);
        $date = $item['_enriched']['datepublished'] ? BackendUtility::date($item['_enriched']['datepublished']) : $item['created']['at'] ?? '';
        return [
            sprintf('%s [%s] - %s', $item['title'], $item['type'] ?? '-', $date),
            $item['UUID'],
            $item['image'] ?? '',
        ];
    }

    protected function getAllRiddles(): array
    {
        $api = GeneralUtility::makeInstance(RiddleApi::class);
        return $api->getRiddleList();
    }

}
