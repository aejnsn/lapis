<?php declare(strict_types=1);

namespace Aejnsn\Lapis;

use Illuminate\Database\Eloquent\Builder;
use Aejnsn\Lapis\Exceptions\UndefinedFilterException;

/**
 * Trait Filterable
 *
 * @package Aejnsn\Lapis
 */
trait Filterable
{
    /**
     * Applies any applicable filters within input to the given Builder.
     *
     * @param Builder $query
     * @param array $input
     * @param Filter|null $filter
     *
     * @return Builder
     */
    public function scopeFilter(Builder $query, array $input, Filter $filter = null): Builder
    {
        /** @var Filter $filter */
        $filter = $filter ?? $this->getFilter();

        return $filter->apply($query, $input);
    }

    /**
     * Returns the filter defined for the model using this trait.
     *
     * @return Filter
     *
     * @throws UndefinedFilterException
     */
    public function getFilter(): Filter
    {
        throw_if(
            is_null($this->filter),
            UndefinedFilterException::class,
            get_class($this)
        );

        return new $this->filter();
    }

    /**
     * Dynamically scopes on a column greater than a given value.
     *
     * @param Builder $q
     * @param string $column
     * @param $value
     * @param string $boolean
     *
     * @return Builder
     */
    public function scopeWhereGreaterThan(Builder $q, string $column, $value, string $boolean = 'and'): Builder
    {
        return $q->where($column, '>', $value, $boolean);
    }

    /**
     * Dynamically scopes on a column greater than or equal to a given value.
     *
     * @param Builder $q
     * @param string $column
     * @param $value
     * @param string $boolean
     *
     * @return Builder
     */
    public function scopeWhereGreaterThanOrEqual(Builder $q, string $column, $value, string $boolean = 'and'): Builder
    {
        return $q->where($column, '>=', $value, $boolean);
    }

    /**
     * Dynamically scopes on a column less than a given value.
     *
     * @param Builder $q
     * @param string $column
     * @param $value
     * @param string $boolean
     *
     * @return Builder
     */
    public function scopeWhereLessThan(Builder $q, string $column, $value, string $boolean = 'and'): Builder
    {
        return $q->where($column, '<', $value, $boolean);
    }

    /**
     * Dynamically scopes on a column less than or equal to a given value.
     *
     * @param Builder $q
     * @param string $column
     * @param $value
     * @param string $boolean
     *
     * @return Builder
     */
    public function scopeWhereLessThanOrEqual(Builder $q, string $column, $value, string $boolean = 'and'): Builder
    {
        return $q->where($column, '<=', $value, $boolean);
    }

    /**
     * Dynamically scopes on a column like a given value.
     *
     * @param Builder $q
     * @param string $column
     * @param $value
     * @param string $boolean
     *
     * @return Builder
     */
    public function scopeWhereLike(Builder $q, string $column, $value, string $boolean = 'and'): Builder
    {
        return $q->where($column, 'LIKE', "%{$value}%", $boolean);
    }

    /**
     * Dynamically scopes on a column not like a given value.
     *
     * @param Builder $q
     * @param string $column
     * @param $value
     * @param string $boolean
     *
     * @return Builder
     */
    public function scopeWhereNotLike(Builder $q, string $column, $value, string $boolean = 'and'): Builder
    {
        return $q->where($column, 'NOT LIKE', "%{$value}%", $boolean);
    }

    /**
     * Dynamically scopes on a column starting with a given value.
     *
     * @param Builder $q
     * @param string $column
     * @param $value
     * @param string $boolean
     *
     * @return Builder
     */
    public function scopeWhereStartsWith(Builder $q, string $column, $value, string $boolean = 'and'): Builder
    {
        return $q->where($column, 'LIKE', "{$value}%", $boolean);
    }

    /**
     * Dynamically scopes on a column ending with a given value.
     *
     * @param Builder $q
     * @param string $column
     * @param $value
     * @param string $boolean
     *
     * @return Builder
     */
    public function scopeWhereEndsWith(Builder $q, string $column, $value, string $boolean = 'and'): Builder
    {
        return $q->where($column, 'LIKE', "%{$value}", $boolean);
    }
}
