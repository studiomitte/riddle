<?php

declare(strict_types=1);

/*
 * This file is part of the "riddle" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace StudioMitte\Riddle\Hooks;

use StudioMitte\Riddle\Api\RiddleApi;
use StudioMitte\Riddle\Utility\RiddleUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Render list of all riddles
 */
class ItemsProcFunc
{
    public function riddleList(array &$config): void
    {
        try {
            foreach ($this->getAllRiddles() as $item) {
                $config['items'][] = $this->getSelectItem($item);
            }
            // @codeCoverageIgnoreStart
        } catch (\Exception $e) {
            $message = GeneralUtility::makeInstance(
                FlashMessage::class,
                $e->getMessage(),
                '',
                ContextualFeedbackSeverity::ERROR
            );
            GeneralUtility::makeInstance(FlashMessageService::class)
                ->getMessageQueueByIdentifier()
                ->enqueue($message);
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * @return array{label: string, value: string, icon: string}
     */
    protected function getSelectItem(array $item): array
    {
        $item = RiddleUtility::enrichRiddleData($item);
        $date = isset($item['_enriched']['datepublished'])
            ? BackendUtility::date($item['_enriched']['datepublished'])
            : ($item['created']['at'] ?? '');
        return [
            'label' => sprintf('%s [%s] - %s', $item['title'] ?? '', $item['type'] ?? '-', $date),
            'value' => (string)($item['UUID'] ?? ''),
            'icon' => (string)($item['image'] ?? ''),
        ];
    }

    protected function getAllRiddles(): array
    {
        return GeneralUtility::makeInstance(RiddleApi::class)->getRiddleList();
    }
}
