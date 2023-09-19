<?php

declare(strict_types=1);

namespace StudioMitte\Riddle\Backend\EventListener;

use TYPO3\CMS\Backend\View\Event\PageContentPreviewRenderingEvent;
use TYPO3\CMS\Core\Attribute\AsEventListener;
use StudioMitte\Riddle\Api\RiddleApi;
use StudioMitte\Riddle\Exception\ApiConfigurationMissingException;
use StudioMitte\Riddle\Utility\RiddleUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

#[AsEventListener(
    identifier: 'riddle/riddle'
)]
final class BackendPreviewEventListener
{
    /** @var string */
    private $template = 'EXT:riddle/Resources/Private/Templates/BackendPreview.html';

    public function __invoke(PageContentPreviewRenderingEvent $event): void
    {
        if ($event->getTable() !== 'tt_content') {
            return;
        }
        
        if ($event->getRecord()['CType'] === 'list' && $event->getRecord()['list_type'] === 'riddle_riddle') {
            $riddleId = RiddleUtility::getRiddleId($event->getRecord()['pi_flexform']);

            $standaloneView = $this->getView();
            try {
                $standaloneView->assignMultiple([
                    'riddleId' => $riddleId,
                    'riddle' => $this->getRiddleData($riddleId),
                ]);
            } catch (ApiConfigurationMissingException $exception) {
                $standaloneView->assign('apiNotConfigured', true);
            }

            $event->setPreviewContent($standaloneView->render());
        }
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
        $item = $api->getRiddleItem($id);
        return RiddleUtility::enrichRiddleData($item);
    }
}