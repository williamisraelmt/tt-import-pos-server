<?php


namespace App\Http\Controllers;


class Grid
{
    /**
     * @var int|null
     */
    private $limit;
    /**
     * @var int|null
     */
    private $offset;
    /**
     * @var string|null
     */
    private $search;
    /**
     * @var array|null
     */
    private $sortBy;

    /**
     * Grid constructor.
     * @param int|null $limit
     * @param int|null $offset
     * @param string|null $searchText
     * @param array|null $sortBy
     */
    public function __construct(?int $limit = 10, ?int $offset = 0, ?string $searchText = null, ?array $sortBy = [])
    {
        $this->limit = $limit ?? 10;
        $this->offset = $offset ?? 0;
        $this->search = $searchText;
        $this->sortBy = $sortBy ?? [];
    }
    /**
     * @return mixed
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param mixed $limit
     */
    public function setLimit($limit): void
    {
        $this->limit = $limit;
    }

    /**
     * @return mixed
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param mixed $offset
     */
    public function setOffset($offset): void
    {
        $this->offset = $offset;
    }

    /**
     * @return mixed
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * @param mixed $search
     */
    public function setSearch($search): void
    {
        $this->search = $search;
    }

    /**
     * @return mixed
     */
    public function getSortBy()
    {
        return $this->sortBy;
    }

    /**
     * @param mixed $sortBy
     */
    public function setSortBy($sortBy): void
    {
        $this->sortBy = $sortBy;
    }
}
