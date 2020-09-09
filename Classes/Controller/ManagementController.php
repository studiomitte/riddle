<?php

declare(strict_types=1);

namespace StudioMitte\Riddle\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use StudioMitte\Riddle\Domain\Dto\LogDemand;
use StudioMitte\Riddle\Domain\Repository\LogRepository;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Http\HtmlResponse;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3Fluid\Fluid\View\ViewInterface;

class ManagementController
{
    /**
     * ModuleTemplate object
     *
     * @var ModuleTemplate
     */
    protected $moduleTemplate;

    /**
     * @var ViewInterface
     */
    protected $view;

    /**
     * @var ServerRequestInterface
     */
    protected $request;

    /** @var LogRepository */
    protected $logRepository;

    public function __construct()
    {
        $this->moduleTemplate = GeneralUtility::makeInstance(ModuleTemplate::class);
        $this->moduleTemplate->getPageRenderer()->loadRequireJsModule('TYPO3/CMS/Backend/Modal');
        $this->getLanguageService()->includeLLFile('EXT:riddle/Resources/Private/Language/locallang.xlf');
        $this->logRepository = GeneralUtility::makeInstance(LogRepository::class);
    }

    public function handleRequest(ServerRequestInterface $request): ResponseInterface
    {
        $this->request = $request;
        $action = $request->getQueryParams()['action'] ?? $request->getParsedBody()['action'] ?? 'overview';
        $this->initializeView($action);

        $result = call_user_func_array([$this, $action . 'Action'], [$request]);
        if ($result instanceof ResponseInterface) {
            return $result;
        }
        $this->moduleTemplate->setContent($this->view->render());
        return new HtmlResponse($this->moduleTemplate->renderContent());
    }

    protected function overviewAction(ServerRequestInterface $request): void
    {
        $demand = LogDemand::initialize($request);

        $count = $this->logRepository->countByDemand($demand);

        if ($count && $demand->isExport()) {
            $this->exportLogs($demand);
        }

        $this->view->assignMultiple([
            'riddles' => $this->logRepository->findByDemand($demand),
            'filter' => $this->logRepository->getGroupingInformation(),
            'demand' => $demand,
            'pagination' => $this->preparePagination($demand, $count),
        ]);
    }

    protected function exportLogs(LogDemand $demand)
    {
        $demand
            ->setLimit(PHP_INT_MAX)
            ->setPage(1);
        $riddles = $this->logRepository->findByDemandForExport($demand);


        $fp = fopen('php://output', 'wb');
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="export.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');
        fputcsv($fp, array_keys($riddles[0]));
        foreach ($riddles as $row) {
            fputcsv($fp, array_values($row));
        };
        fclose($fp);
        die;
    }

    protected function preparePagination(LogDemand $demand, int $count): array
    {
        $numberOfPages = ceil($count / $demand->getLimit());
        $endRecord = $demand->getOffset() + $demand->getLimit();
        if ($endRecord > $count) {
            $endRecord = $count;
        }

        $pagination = [
            'current' => $demand->getPage(),
            'numberOfPages' => $numberOfPages,
            'hasLessPages' => $demand->getPage() > 1,
            'hasMorePages' => $demand->getPage() < $numberOfPages,
            'startRecord' => $demand->getOffset() + 1,
            'endRecord' => $endRecord
        ];
        if ($pagination['current'] < $pagination['numberOfPages']) {
            $pagination['nextPage'] = $pagination['current'] + 1;
        }
        if ($pagination['current'] > 1) {
            $pagination['previousPage'] = $pagination['current'] - 1;
        }
        return $pagination;
    }

    /**
     * @param string $templateName
     */
    protected function initializeView(string $templateName): void
    {
        $this->view = GeneralUtility::makeInstance(StandaloneView::class);
        $this->view->setTemplate($templateName);
        $this->view->setTemplateRootPaths(['EXT:riddle/Resources/Private/Templates/Management']);
        $this->view->setPartialRootPaths(['EXT:riddle/Resources/Private/Partials']);
        $this->view->setLayoutRootPaths(['EXT:riddle/Resources/Private/Layouts']);
    }

    /**
     * @return LanguageService
     */
    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }

    /**
     * @return BackendUserAuthentication
     */
    protected function getBackendUserAuthentication(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}
