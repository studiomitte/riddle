<?php

declare(strict_types=1);

/*
 * This file is part of the "riddle" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace StudioMitte\Riddle\Backend\EventListener;

use StudioMitte\Riddle\Api\RiddleApi;
use StudioMitte\Riddle\Exception\ApiConfigurationMissingException;
use StudioMitte\Riddle\Utility\RiddleUtility;
use TYPO3\CMS\Backend\View\Event\PageContentPreviewRenderingEvent;
use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Core\View\ViewFactoryInterface;

#[AsEventListener(identifier: 'riddle/backend-preview')]
final readonly class BackendPreviewEventListener
{
    private const TEMPLATE = 'EXT:riddle/Resources/Private/Templates/BackendPreview.html';

    public function __construct(
        private ViewFactoryInterface $viewFactory,
        private RiddleApi $riddleApi,
    ) {}

    public function __invoke(PageContentPreviewRenderingEvent $event): void
    {
        if ($event->getTable() !== 'tt_content' || $event->getRecordType() !== 'riddle_riddle') {
            return;
        }

        $rawRecord = $event->getRecord()->getRawRecord();
        $flexForm = $rawRecord?->has('pi_flexform') ? (string)$rawRecord->get('pi_flexform') : '';
        $riddleId = RiddleUtility::getRiddleId($flexForm);

        $view = $this->viewFactory->create(new ViewFactoryData(
            templatePathAndFilename: GeneralUtility::getFileAbsFileName(self::TEMPLATE),
            request: $event->getPageLayoutContext()->getCurrentRequest(),
        ));
        $view->assignMultiple([
            'dateTime' => $GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy'] . ' ' . $GLOBALS['TYPO3_CONF_VARS']['SYS']['hhmm'],
            'lll' => 'LLL:EXT:riddle/Resources/Private/Language/locallang.xlf:',
            'riddleId' => $riddleId,
        ]);
        if ($riddleId !== '') {
            try {
                $view->assign('riddle', RiddleUtility::enrichRiddleData($this->riddleApi->getRiddleItem($riddleId)));
            } catch (ApiConfigurationMissingException) {
                $view->assign('apiNotConfigured', true);
            } catch (\Exception) {
                // An unreachable API must not break the page module, the template shows a hint instead
                $view->assign('riddle', []);
            }
        }

        $event->setPreviewContent($view->render());
    }
}
