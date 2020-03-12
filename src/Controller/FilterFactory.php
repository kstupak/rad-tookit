<?php

/*
 * This file is part of "rad-tookit".
 *
 * (c) Kostiantyn Stupak <konstantin.stupak@gimmemore.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace KStupak\RAD\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use KStupak\RAD\Model\Filter\AvailableFiltersMap;
use KStupak\RAD\Model\Pagination;
use KStupak\RAD\Model\Search;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

trait FilterFactory
{
    /** @var AvailableFiltersMap */
    private $filterMap;

    private function getFilters(Request $request): Collection
    {
        $filters = new ArrayCollection();
        foreach ($request->query->all() as $name => $value) {
            if (!$this->filterMap->isAKnownFilter($name)) { continue; }

            $inverted = strpos('!', $value, 0);
            $class = $this->filterMap->getFilterClassByName($name);
            $filter = \call_user_func(
                [$class, 'createForValue'],
                $inverted
                    ? str_replace('!', '', $value)
                    : $value, $inverted);

            $filters->set($name, $filter);
        }

        return $filters;
    }

    private function extractFilterQueryParameters(array $input) : array
    {
        $reservedKeys = ['page', 'take', 'query'];

        return array_filter($input, static fn(string $key) => ! in_array($key, $reservedKeys), ARRAY_FILTER_USE_KEY);
    }

    private function extractPagination(Request $request) : Pagination
    {
        return new Pagination(
            0,
            (int) $request->get('page', 1),
            (int) $request->get('take', Pagination::DEFAULT_BATCH_SIZE)
        );
    }

    private function updateResponseWithPaginationHeaders(
        Request $request,
        Response $response,
        Search $searchConfiguration
    ) : void {
        $queryParams = $this->extractFilterQueryParameters($request->query->all());
        [$referrerUrl] = explode('?', $request->server->get('HTTP_REFERER'));

        $headers   = [];
        $relations = [
            'next'  => 'getNext',
            'last'  => 'getLast',
            'first' => 'getFirst',
            'prev'  => 'getPrevious',
        ];

        $headerTemplate = '<%s?%s&take=%d&page=%d>; rel="%s"';

        $params = $searchConfiguration->getQuery()
            ? array_merge(['query' => $searchConfiguration->getQuery()], $queryParams)
            : $queryParams;

        $queryParams = http_build_query($params);

        foreach ($relations as $relation => $method) {
            $page      = call_user_func([$searchConfiguration->getPagination(), $method]);
            $headers[] = sprintf($headerTemplate, $referrerUrl, $queryParams, $searchConfiguration->getPagination()->getPageSize(), $page, $relation);
        }

        $response->headers->set('Link', join(',', $headers));
        $response->headers->set('X-Total', (string) $searchConfiguration->getPagination()->getTotal());
    }

    private function getSearchConfigFromRequest(Request $request): Search
    {
        return new Search($request->get('query'), $this->getFilters($request), $this->extractPagination($request));
    }
}
