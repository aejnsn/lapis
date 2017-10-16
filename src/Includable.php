<?php declare(strict_types=1);

namespace Aejnsn\Lapis;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait Includable
 *
 * @package Aejnsn\Lapis
 */
trait Includable
{
    /**
     * Eager load includes given in the input array.
     *
     * @param Builder $query
     * @param array $includes
     *
     * @return Builder
     */
    public function scopeInclude(Builder $query, array $includes): Builder
    {
        return $query->with($includes);
    }
}
