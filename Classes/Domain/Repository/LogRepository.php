<?php

declare(strict_types=1);

/*
 * This file is part of the "riddle" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace StudioMitte\Riddle\Domain\Repository;

use StudioMitte\Riddle\Domain\Dto\LogDemand;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;

class LogRepository
{
    private const TABLE = 'tx_riddle_log';

    public function __construct(
        private readonly ConnectionPool $connectionPool,
    ) {}

    public function add(array $item): void
    {
        if (!empty($item)) {
            $item['crdate'] = time();
            $this->getConnection()->insert(self::TABLE, $item);
        }
    }

    public function countByDemand(LogDemand $demand): int
    {
        $queryBuilder = $this->getConnection()->createQueryBuilder();
        $queryBuilder->count('uid')->from(self::TABLE);
        $this->applyDemandConstraints($queryBuilder, $demand);
        return (int)$queryBuilder->executeQuery()->fetchOne();
    }

    public function findByDemand(LogDemand $demand): array
    {
        $queryBuilder = $this->getConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(self::TABLE)
            ->orderBy('uid', 'desc')
            ->setMaxResults($demand->getLimit())
            ->setFirstResult($demand->getOffset());
        $this->applyDemandConstraints($queryBuilder, $demand);
        return $queryBuilder->executeQuery()->fetchAllAssociative();
    }

    public function findByDemandForExport(LogDemand $demand): array
    {
        $rows = $this->findByDemand($demand);

        foreach ($rows as &$row) {
            $row['crdate'] = date('d.m.Y H:i', (int)$row['crdate']);
            $json = json_decode((string)$row['full_json'], true) ?: [];
            foreach ($json['lead2'] ?? [] as $leadFieldName => $leadFields) {
                $row['raw_lead_' . $leadFieldName] = $leadFields['value'] ?? '';
            }

            foreach ($json['answers'] ?? [] as $answer) {
                $questionId = $answer['questionId'] ?? '';
                $row['question_' . $questionId] = $answer['question'] ?? '';
                $row['question_' . $questionId . '_answer'] = $answer['answer'] ?? '';
                $row['question_' . $questionId . '_correct'] = $answer['correct'] ?? '';
            }

            unset($row['full_json']);
        }

        return $rows;
    }

    public function getGroupingInformation(): array
    {
        $groupingInformation = [];
        $columns = ['riddle_type' => 'type', 'riddle_id' => 'id'];
        foreach ($columns as $field => $label) {
            $rows = $this->getConnection()->createQueryBuilder()
                ->select($field)
                ->from(self::TABLE)
                ->groupBy($field)
                ->executeQuery()
                ->fetchAllAssociative();

            foreach ($rows as $row) {
                $groupingInformation[$label][] = $row[$field];
            }
        }

        return $groupingInformation;
    }

    private function applyDemandConstraints(QueryBuilder $queryBuilder, LogDemand $demand): void
    {
        $constraints = [];
        if ($demand->getType()) {
            $constraints[] = $queryBuilder->expr()->eq(
                'riddle_type',
                $queryBuilder->createNamedParameter($demand->getType())
            );
        }

        if ($demand->getId()) {
            $constraints[] = $queryBuilder->expr()->eq(
                'riddle_id',
                $queryBuilder->createNamedParameter($demand->getId(), Connection::PARAM_INT)
            );
        }

        if (!empty($constraints)) {
            $queryBuilder->where(...$constraints);
        }
    }

    private function getConnection(): Connection
    {
        return $this->connectionPool->getConnectionForTable(self::TABLE);
    }
}
