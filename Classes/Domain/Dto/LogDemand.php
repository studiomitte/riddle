<?php

declare(strict_types=1);

namespace StudioMitte\Riddle\Domain\Dto;

use Psr\Http\Message\ServerRequestInterface;

class LogDemand
{
    /** @var string */
    protected $type = '';

    /** @var int */
    protected $id = 0;

    /** @var int */
    protected $limit = 50;

    /** @var int */
    protected $page;

    /** @var bool */
    protected $export = false;

    public static function initialize(ServerRequestInterface $request): self
    {
        $demand = new self();
        $demand->type = (string)($request->getQueryParams()['type'] ?? $request->getParsedBody()['type'] ?? 0);
        $demand->id = (int)($request->getQueryParams()['id'] ?? $request->getParsedBody()['id'] ?? 0);
        $demand->page = (int)($request->getQueryParams()['page'] ?? $request->getParsedBody()['page'] ?? 1);
        $demand->export = (bool)($request->getQueryParams()['export'] ?? $request->getParsedBody()['export'] ?? false);

        return $demand;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * The current Page of the paginated redirects
     *
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * Offset for the current set of records
     *
     * @return int
     */
    public function getOffset(): int
    {
        return ($this->page - 1) * $this->limit;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return bool
     */
    public function isExport(): bool
    {
        return $this->export;
    }

    /**
     * @param int $limit
     * @return LogDemand
     */
    public function setLimit(int $limit): LogDemand
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @param int $page
     * @return LogDemand
     */
    public function setPage(int $page): LogDemand
    {
        $this->page = $page;
        return $this;
    }
}
