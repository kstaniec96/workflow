<?php

namespace Simpler\Components\Database\Interfaces;

use Simpler\Components\Database\Model;
use Simpler\Components\Database\Paginator;

interface PaginatorInterface
{
    /**
     * @param int $pageLimit
     * @param Model $connect
     * @return Paginator
     */
    public static function init(int $pageLimit, Model $connect): Paginator;

    /**
     * @param string $columns
     * @return array
     */
    public function get(string $columns = ''): array;

    /**
     * @return int
     */
    public function currentPage(): int;

    /**
     * @return int
     */
    public function lastPage(): int;

    /**
     * @return int
     */
    public function prevPage(): int;

    /**
     * @return int
     */
    public function nextPage(): int;

    /**
     * @return array
     */
    public function pages(): array;

    /**
     * @param int $page
     * @return string
     */
    public function pageLink(int $page): string;

    /**
     * @return int
     */
    public function total(): int;
}
