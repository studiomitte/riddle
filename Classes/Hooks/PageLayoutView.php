<?php
declare(strict_types=1);

namespace StudioMitte\Riddle\Hooks;

/**
 * This file is part of the "riddle" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use StudioMitte\Riddle\Api\RiddleApi;
use StudioMitte\Riddle\Exception\ApiConfigurationMissingException;
use StudioMitte\Riddle\Utility\RiddleUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

class PageLayoutView
{

    /** @var string */
    private $template = 'EXT:riddle/Resources/Private/Templates/BackendPreview.html';

    /**
     * Returns information about this extension's plugin
     *
     * @param array $params Parameters to the hook
     * @return string Information about pi1 plugin
     */
    public function getExtensionSummary(array $params): string
    {
        $riddleId = RiddleUtility::getRiddleId((string)$params['row']['pi_flexform']);

        $standaloneView = $this->getView();
        try {
            $standaloneView->assignMultiple([
                'riddleId' => $riddleId,
                'riddle' => $this->getRiddleData($riddleId),
                'riddleV2' => !MathUtility::canBeInterpretedAsInteger($riddleId)
            ]);
        } catch (ApiConfigurationMissingException $exception) {
            $standaloneView->assign('apiNotConfigured', true);
        }
        return $standaloneView->render();
    }

    protected function getView(): StandaloneView
    {
        $standaloneView = GeneralUtility::makeInstance(StandaloneView::class);
        $standaloneView->setTemplatePathAndFilename(GeneralUtility::getFileAbsFileName($this->template));
        $standaloneView->assignMultiple([
            'dateTime' => $GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy'] . ' ' . $GLOBALS['TYPO3_CONF_VARS']['SYS']['hhmm'],
            'lll' => 'LLL:EXT:riddle/Resources/Private/Language/locallang.xlf:',
        ]);
        return $standaloneView;
    }

    /**
     * @param string|int $id
     * @return array
     */
    protected function getRiddleData($id): array
    {
        $api = GeneralUtility::makeInstance(RiddleApi::class);
        if (MathUtility::canBeInterpretedAsInteger($id)) {
            $item = $api->getRiddleItem($id);
        } else {
            $item = $api->getRiddleItemV2($id);
        }
        return RiddleUtility::enrichRiddleData($item);
    }
}
