<?php

declare(strict_types=1);

namespace StudioMitte\Riddle\Plugin;

/**
 * This file is part of the "riddle" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use StudioMitte\Riddle\Api\RiddleApi;
use StudioMitte\Riddle\Utility\RiddleUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class RiddlePlugin extends ContentObjectRenderer
{

    /** @var ContentObjectRenderer */
    protected $cObj;

    public function run(): string
    {
        $riddleId = RiddleUtility::getRiddleId($this->cObj->data['pi_flexform']);
        if (!$riddleId) {
            return '';
        }

        return $this->getRiddleHtml($riddleId);
    }

    protected function getRiddleHtml(int $id): string
    {
        $response = GeneralUtility::makeInstance(RiddleApi::class)->getEmbedCode($id);
        return (string)($response['response'] ?? '');
    }
}
