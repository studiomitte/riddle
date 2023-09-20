<?php

declare(strict_types=1);

namespace StudioMitte\Riddle\Domain\Repository;

use StudioMitte\Riddle\Domain\Dto\LogDemand;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class LogRepository
{
    private const TABLE = 'tx_riddle_log';

    public function add(array $item): void
    {
        if (!empty($item)) {
            $item['crdate'] = time();
            $this->getConnection()->insert(self::TABLE, $item);
        }
    }

    public function countByDemand(LogDemand $demand): int
    {
        return $this->getQueryBuilderForDemand($demand)->execute()->rowCount();
    }

    protected function getQueryBuilderForDemand(LogDemand $demand): QueryBuilder
    {
        $queryBuilder = $this->getConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(self::TABLE)
            ->orderBy('uid', 'desc');
        $constraints = [];
        if ($demand->getType()) {
            $constraints[] = $queryBuilder->expr()->eq(
                'riddle_type',
                $queryBuilder->createNamedParameter($demand->getType(), \PDO::PARAM_STR)
            );
        }

        if ($demand->getId()) {
            $constraints[] = $queryBuilder->expr()->eq(
                'riddle_id',
                $queryBuilder->createNamedParameter($demand->getId(), \PDO::PARAM_INT)
            );
        }

        if (!empty($constraints)) {
            $queryBuilder->where(...$constraints);
        }
        return $queryBuilder;
    }

    public function findByDemand(LogDemand $demand): array
    {
        return $this->getQueryBuilderForDemand($demand)
            ->setMaxResults($demand->getLimit())
            ->setFirstResult($demand->getOffset())
            ->execute()
            ->fetchAll();
    }

    public function findByDemandForExport(LogDemand $demand): array
    {
        $rows = $this->findByDemand($demand);

        foreach ($rows as &$row) {
            $row['crdate'] = date('d.m.Y H:i', $row['crdate']);
            $json = json_decode($row['full_json'], true);
            foreach ($json['lead2'] ?? [] as $leadFieldName => $leadFields) {
                $row['raw_lead_' . $leadFieldName] = $leadFields['value'];
            }

            foreach ($json['answers'] ?? [] as $answer) {
                $questionId = $answer['questionId'];
                $row['question_' . $questionId] = $answer['question'];
                $row['question_' . $questionId . '_answer'] = $answer['answer'];
                $row['question_' . $questionId . '_correct'] = $answer['correct'];
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
            $queryBuilder = $this->getConnection()->createQueryBuilder();

            $rows = $queryBuilder
                ->select($field)
                ->from(self::TABLE)->groupBy($field)->executeQuery()
                ->fetchAll();

            foreach ($rows as $row) {
                $groupingInformation[$label][] = $row[$field];
            }
        }

        return $groupingInformation;
    }

    protected function getConnection(): Connection
    {
        return GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable(self::TABLE);
    }
}
