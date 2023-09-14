<?php

declare(strict_types=1);

namespace StudioMitte\Riddle\Controller;

/**
 * This file is part of the "riddle" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use StudioMitte\Riddle\Api\RiddleApi;
use StudioMitte\Riddle\Utility\RiddleUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class PluginController extends ContentObjectRenderer
{
    protected ContentObjectRenderer $cObj;

    public function __construct() {
        $this->cObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);
    }

    public function run(): string
    {
        DebugUtility::debug($this->data);
        $flexforms = (string)$this->cObj->data['pi_flexform'];
        if (!$flexforms) {
            return '';
        }
        $riddleId = RiddleUtility::getRiddleId($flexforms);
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
