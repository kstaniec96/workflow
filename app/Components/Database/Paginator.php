<?php
/**
 * This class model helper
 * Extension for Paginate results.
 *
 * @package Simpler
 * @subpackage Database
 * @version 2.0
 */

namespace Simpler\Components\Database;

use Simpler\Components\Database\Interfaces\PaginatorInterface;
use Simpler\Components\Http\Url\QueryURL;
use Exception;
use RuntimeException;

class Paginator implements PaginatorInterface
{
    /** @var int */
    private static int $totalRows = 0;

    /** @var int */
    private static int $pageLimit = 20;

    /** @var Model */
    private static $connect;

    /** @var int */
    private static int $currentPage = 1;

    /** @var int */
    private static int $firstPage = 1;

    /** @var int */
    private static int $lastPage = 1;

    /** @var int */
    private static int $prevPage = 1;

    /** @var int */
    private static int $nextPage = 1;

    /**
     * @param int $pageLimit
     * @param Model $connect
     * @return Paginator
     */
    public static function init(int $pageLimit, Model $connect): Paginator
    {
        self::$connect = $connect;

        self::$totalRows = $connect->count();
        self::$pageLimit = $pageLimit;

        self::$currentPage = self::getCurrentPage();
        self::$firstPage = self::getFirstPage();
        self::$lastPage = self::getLastPage();
        self::$prevPage = self::getPrevPage();
        self::$nextPage = self::getNextPage();

        return new self();
    }

    /**
     * @param string $columns
     * @return array
     */
    public function get(string $columns = ''): array
    {
        try {
            return self::$connect
                ->limit(self::$pageLimit)
                ->offset((self::$currentPage - 1) * self::$pageLimit)
                ->asObject()
                ->get($columns);
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * @return int
     */
    public function currentPage(): int
    {
        return self::$currentPage;
    }

    /**
     * @return int
     */
    public function lastPage(): int
    {
        return self::$lastPage;
    }

    /**
     * @return int
     */
    public function prevPage(): int
    {
        return self::$prevPage;
    }

    /**
     * @return int
     */
    public function nextPage(): int
    {
        return self::$nextPage;
    }

    /**
     * @return array
     */
    public function pages(): array
    {
        $pages = [];

        for ($page = self::$firstPage; $page <= self::$lastPage; $page++) {
            $pages[] = $page;
        }

        return $pages;
    }

    /**
     * @param int $page
     * @return string
     */
    public function pageLink(int $page): string
    {
        if ($page > self::$lastPage || $page < self::$firstPage) {
            $page = 1;
        }

        return QueryURL::build(['page' => $page]);
    }

    /**
     * @return int
     */
    public function total(): int
    {
        return self::$totalRows;
    }

    /**
     * @return int
     */
    private static function getCurrentPage(): int
    {
        $currentPage = (int)(QueryURL::query()->get('page')->value ?? 1);

        return compare($currentPage, 0) ? 1 : $currentPage;
    }

    /**
     * @return int
     */
    private static function getFirstPage(): int
    {
        return 1;
    }

    /**
     * @return int
     */
    private static function getLastPage(): int
    {
        if (self::$totalRows <= 1 && self::$pageLimit > 0) {
            return 1;
        }

        return ceil(self::$totalRows / self::$pageLimit);
    }

    /**
     * @return int
     */
    private static function getPrevPage(): int
    {
        if (self::$currentPage <= 1) {
            return 1;
        }

        return self::$currentPage - 1;
    }

    /**
     * @return int
     */
    private static function getNextPage(): int
    {
        if (compare(self::$currentPage, self::$lastPage)) {
            return self::$lastPage;
        }

        return self::$currentPage + 1;
    }
}
