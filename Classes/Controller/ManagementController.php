<?php

declare(strict_types=1);

/*
 * This file is part of the "riddle" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace StudioMitte\Riddle\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use StudioMitte\Riddle\Domain\Dto\LogDemand;
use StudioMitte\Riddle\Domain\Repository\LogRepository;
use TYPO3\CMS\Backend\Attribute\AsController;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Http\Response;

#[AsController]
final readonly class ManagementController
{
    public function __construct(
        private ModuleTemplateFactory $moduleTemplateFactory,
        private LogRepository $logRepository,
    ) {}

    public function handleRequest(ServerRequestInterface $request): ResponseInterface
    {
        $demand = LogDemand::initialize($request);
        $count = $this->logRepository->countByDemand($demand);

        if ($count && $demand->isExport()) {
            return $this->exportLogs($demand);
        }

        $view = $this->moduleTemplateFactory->create($request);
        $view->setTitle('Riddle');
        $view->assignMultiple([
            'riddles' => $this->logRepository->findByDemand($demand),
            'filter' => $this->logRepository->getGroupingInformation(),
            'demand' => $demand,
            'pagination' => $this->preparePagination($demand, $count),
        ]);
        return $view->renderResponse('Management/Overview');
    }

    private function exportLogs(LogDemand $demand): ResponseInterface
    {
        $demand
            ->setLimit(PHP_INT_MAX)
            ->setPage(1);
        $riddles = $this->logRepository->findByDemandForExport($demand);

        $columns = [];
        foreach ($riddles as $row) {
            $columns += array_fill_keys(array_keys($row), '');
        }

        $stream = fopen('php://temp', 'w+b');
        fputcsv($stream, array_keys($columns), ',', '"', '');
        foreach ($riddles as $row) {
            fputcsv($stream, array_values(array_replace($columns, $row)), ',', '"', '');
        }
        rewind($stream);
        $csv = (string)stream_get_contents($stream);
        fclose($stream);

        $response = (new Response())
            ->withHeader('Content-Type', 'text/csv; charset=utf-8')
            ->withHeader('Content-Disposition', 'attachment; filename="riddle-export.csv"')
            ->withHeader('Cache-Control', 'no-cache, no-store');
        $response->getBody()->write($csv);
        return $response;
    }

    private function preparePagination(LogDemand $demand, int $count): array
    {
        $numberOfPages = (int)ceil($count / $demand->getLimit());
        $endRecord = min($demand->getOffset() + $demand->getLimit(), $count);

        $pagination = [
            'current' => $demand->getPage(),
            'numberOfPages' => $numberOfPages,
            'hasLessPages' => $demand->getPage() > 1,
            'hasMorePages' => $demand->getPage() < $numberOfPages,
            'startRecord' => $demand->getOffset() + 1,
            'endRecord' => $endRecord,
        ];
        if ($pagination['current'] < $pagination['numberOfPages']) {
            $pagination['nextPage'] = $pagination['current'] + 1;
        }
        if ($pagination['current'] > 1) {
            $pagination['previousPage'] = $pagination['current'] - 1;
        }
        return $pagination;
    }
}
