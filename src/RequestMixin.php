<?php declare(strict_types=1);

namespace Aejnsn\Lapis;

use Closure;
use Aejnsn\Lapis\Exceptions\InvalidFilterQueryException;

/**
 * Class RequestMixin
 *
 * @package Aejnsn\Lapis
 */
class RequestMixin
{
    /**
     * Returns an array of filters from parameters in request URL.
     *
     * @return Closure
     */
    public function filters(): Closure
    {
        return function (): array {
            $filter = static::input('filter', []);

            throw_unless(is_array($filter), InvalidFilterQueryException::class);

            return $filter;
        };
    }

    /**
     * Returns an array of includes from parameters in request URL.
     *
     * @return Closure
     */
    public function includes(): Closure
    {
        return function (): array {
            return collect(explode(',', static::input('include', '')))
                ->map(function ($include) {
                    return trim($include);
                })->filter()->toArray();
        };
    }
}
