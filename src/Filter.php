<?php declare(strict_types=1);

namespace Aejnsn\Lapis;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Aejnsn\Lapis\Exceptions\FilterFieldNotFoundException;

/**
 * Class Filter
 *
 * @package Aejnsn\Lapis
 */
abstract class Filter
{
    /**
     * An instance of an Eloquent Builder.
     *
     * @var Builder
     */
    protected $query;

    /**
     * A Collection of the passed input.
     *
     * @var Collection
     */
    protected $input;

    /**
     * An array of fields on which filters can be applied.
     *
     * @var array
     */
    protected $filterableFields = [];

    /**
     * Maps local scope methods on Filterable to an abbreviated string.
     *
     * @var array
     */
    protected $operatorMappings = [
        'eq' => 'where',

        'lt' => 'whereLessThan',
        'gt' => 'whereGreaterThan',
        'lte' => 'whereLessThanOrEqual',
        'gte' => 'whereGreaterThanOrEqual',

        'like' => 'whereLike',
        'nlike' => 'whereNotLike',
        'starts' => 'whereStartsWith',
        'ends' => 'whereEndsWith',
    ];

    /**
     * Applies this filter given the query and input.
     *
     * @param $query
     * @param array $input
     *
     * @return Builder
     */
    public function apply($query, array $input = []): Builder
    {
        $this->query = $query;
        $this->input = collect($input);

        $this->applyFilterInput();

        return $this->query;
    }

    /**
     * Performs the actual filtering operation.
     *
     * @return void
     */
    protected function applyFilterInput()
    {
        /**
         * @var Collection $withRelations
         * @var Collection $withoutRelations
         */
        [$withRelations, $withoutRelations] = $this->input->partition(function ($value, $key) {
            return $this->hasRelations($key);
        });

        $withRelations->each(function ($value, $field) {
            $relations = $this->getRelationString($field);

            $this->query->with($relations)->whereHas($relations, function ($q) use ($field, $relations, $value) {
                $this->queryByField($q, $field, $value, $relations);
            });
        });

        $withoutRelations->each(function ($value, $field) {
            $this->queryByField($this->query, $field, $value);
        });
    }

    /**
     * Determines whether the given field contains relations by checking for separators.
     *
     * @param $field
     *
     * @return bool
     */
    protected function hasRelations(string $field): bool
    {
        return str_contains($field, '.');
    }

    /**
     * Returns the relation string, minus the final field name.
     *
     * @param $field
     *
     * @return string
     */
    protected function getRelationString(string $field): string
    {
        $relations = explode('.', $field);

        return implode('.', array_slice($relations, 0, count($relations) - 1));
    }

    /**
     * Adds a where clause if operator is unmapped (match exact), otherwise use the appropriately mapped scope.
     *
     * @param Builder $query
     * @param string $field
     * @param string $value
     * @param string $relations
     *
     * @return void
     *
     * @throws FilterFieldNotFoundException
     */
    protected function queryByField(Builder $query, string $field, string $value, $relations = '')
    {
        preg_match('/^((?>[a-z_]+)(?:\.))*(?<field>[a-z_]+)(\{(?<operator>[a-z]+)\})*$/i', $field, $fieldMatch);

        $matchedField = snake_case(array_get($fieldMatch, 'field'));
        $matchedOperator = array_get($fieldMatch, 'operator', 'eq');

        /** @var Filterable $filterableModel */
        $filterableModel = collect(explode('.', $relations))
            ->filter()
            ->reduce(function ($carry, $relation) {
                return $carry->{$relation}()->getModel();
            }, $this->query->getModel());

        throw_unless(
            collect($filterableModel->getFilter()->getFields())->contains($matchedField),
            FilterFieldNotFoundException::class,
            $field
        );

        $scope = $this->operatorMappings[$matchedOperator];
        $query->{$scope}($matchedField, $value);
    }

    /**
     * Returns the fields on which filters can be applied.
     *
     * @return array
     */
    public function getFields(): array
    {
        return collect($this->filterableFields)->map(function ($field) {
            return snake_case($field);
        })->toArray();
    }
}
