<?php

namespace Aejnsn\Lapis\Tests;

use Aejnsn\Lapis\Exceptions\FilterFieldNotFoundException;
use Aejnsn\Lapis\Exceptions\UndefinedFilterException;
use Aejnsn\Lapis\Tests\Stubs\BadFilterableStub;
use Orchestra\Testbench\TestCase;
use Illuminate\Database\Eloquent\Builder;
use Aejnsn\Lapis\Tests\Stubs\SiteFilterableStub;

/**
 * Class FilterableTest
 *
 * @package Aejnsn\Lapis\Tests
 */
class FilterableTest extends TestCase
{
    public function testItAppliesEqualFilterByDefault()
    {
        $filterable = new SiteFilterableStub();

        /** @var Builder $builder */
        $builder = $filterable->filter([
            'temperature' => '100',
            'project.name' => 'Rebuild',
        ]);

        $wheres = collect($builder->getQuery()->wheres)
            ->groupBy(function ($where) {
                return $where['type'];
            });

        $wheres->get('Basic')->each(function ($where) {
            static::assertArraySubset(['operator' => '='], $where);
        });

        $wheres->get('Exists')->each(function ($where) {
            static::assertArraySubset(['operator' => '='], $where['query']->wheres[0]);
        });
    }

    public function testItAppliesEqualFilterCorrectly()
    {
        $filterable = new SiteFilterableStub();

        /** @var Builder $builder */
        $builder = $filterable->filter([
            'temperature{eq}' => '100',
            'project.name{eq}' => 'Rebuild',
        ]);

        $wheres = collect($builder->getQuery()->wheres)
            ->groupBy(function ($where) {
                return $where['type'];
            });

        $wheres->get('Basic')->each(function ($where) {
            static::assertArraySubset([
                'column' => 'temperature',
                'operator' => '=',
                'value' => '100',
            ], $where);
        });

        $wheres->get('Exists')->each(function ($where) {
            static::assertArraySubset([
                'column' => 'name',
                'operator' => '=',
                'value' => 'Rebuild',
            ], $where['query']->wheres[1]);
        });
    }

    public function testItAppliesLessThanFilterCorrectly()
    {
        $filterable = new SiteFilterableStub();

        /** @var Builder $builder */
        $builder = $filterable->filter([
            'temperature{lt}' => '100',
            'project.createdAt{lt}' => '2017-11-01',
        ]);

        $wheres = collect($builder->getQuery()->wheres)
            ->groupBy(function ($where) {
                return $where['type'];
            });

        $wheres->get('Basic')->each(function ($where) {
            static::assertArraySubset([
                'column' => 'temperature',
                'operator' => '<',
                'value' => '100',
            ], $where);
        });

        $wheres->get('Exists')->each(function ($where) {
            static::assertArraySubset([
                'column' => 'created_at',
                'operator' => '<',
                'value' => '2017-11-01',
            ], $where['query']->wheres[1]);
        });
    }

    public function testItAppliesGreaterThanFilterCorrectly()
    {
        $filterable = new SiteFilterableStub();

        /** @var Builder $builder */
        $builder = $filterable->filter([
            'temperature{gt}' => '100',
            'project.createdAt{gt}' => '2017-11-01',
        ]);

        $wheres = collect($builder->getQuery()->wheres)
            ->groupBy(function ($where) {
                return $where['type'];
            });

        $wheres->get('Basic')->each(function ($where) {
            static::assertArraySubset([
                'column' => 'temperature',
                'operator' => '>',
                'value' => '100',
            ], $where);
        });

        $wheres->get('Exists')->each(function ($where) {
            static::assertArraySubset([
                'column' => 'created_at',
                'operator' => '>',
                'value' => '2017-11-01',
            ], $where['query']->wheres[1]);
        });
    }

    public function testItAppliesLessThanOrEqualFilterCorrectly()
    {
        $filterable = new SiteFilterableStub();

        /** @var Builder $builder */
        $builder = $filterable->filter([
            'temperature{lte}' => '100',
            'project.createdAt{lte}' => '2017-11-01',
        ]);

        $wheres = collect($builder->getQuery()->wheres)
            ->groupBy(function ($where) {
                return $where['type'];
            });

        $wheres->get('Basic')->each(function ($where) {
            static::assertArraySubset([
                'column' => 'temperature',
                'operator' => '<=',
                'value' => '100',
            ], $where);
        });

        $wheres->get('Exists')->each(function ($where) {
            static::assertArraySubset([
                'column' => 'created_at',
                'operator' => '<=',
                'value' => '2017-11-01',
            ], $where['query']->wheres[1]);
        });
    }

    public function testItAppliesGreaterThanOrEqualFilterCorrectly()
    {
        $filterable = new SiteFilterableStub();

        /** @var Builder $builder */
        $builder = $filterable->filter([
            'temperature{gte}' => '100',
            'project.createdAt{gte}' => '2017-11-01',
        ]);

        $wheres = collect($builder->getQuery()->wheres)
            ->groupBy(function ($where) {
                return $where['type'];
            });

        $wheres->get('Basic')->each(function ($where) {
            static::assertArraySubset([
                'column' => 'temperature',
                'operator' => '>=',
                'value' => '100',
            ], $where);
        });

        $wheres->get('Exists')->each(function ($where) {
            static::assertArraySubset([
                'column' => 'created_at',
                'operator' => '>=',
                'value' => '2017-11-01',
            ], $where['query']->wheres[1]);
        });
    }

    public function testItAppliesLikeFilterCorrectly()
    {
        $filterable = new SiteFilterableStub();

        /** @var Builder $builder */
        $builder = $filterable->filter([
            'name{like}' => 'John',
            'project.name{like}' => 'Rebuild',
        ]);

        $wheres = collect($builder->getQuery()->wheres)
            ->groupBy(function ($where) {
                return $where['type'];
            });

        $wheres->get('Basic')->each(function ($where) {
            static::assertArraySubset([
                'column' => 'name',
                'operator' => 'LIKE',
                'value' => '%John%',
            ], $where);
        });

        $wheres->get('Exists')->each(function ($where) {
            static::assertArraySubset([
                'column' => 'name',
                'operator' => 'LIKE',
                'value' => '%Rebuild%',
            ], $where['query']->wheres[1]);
        });
    }

    public function testItAppliesNotLikeFilterCorrectly()
    {
        $filterable = new SiteFilterableStub();

        /** @var Builder $builder */
        $builder = $filterable->filter([
            'name{nlike}' => 'John',
            'project.name{nlike}' => 'Rebuild',
        ]);

        $wheres = collect($builder->getQuery()->wheres)
            ->groupBy(function ($where) {
                return $where['type'];
            });

        $wheres->get('Basic')->each(function ($where) {
            static::assertArraySubset([
                'column' => 'name',
                'operator' => 'NOT LIKE',
                'value' => '%John%',
            ], $where);
        });

        $wheres->get('Exists')->each(function ($where) {
            static::assertArraySubset([
                'column' => 'name',
                'operator' => 'NOT LIKE',
                'value' => '%Rebuild%',
            ], $where['query']->wheres[1]);
        });
    }

    public function testItAppliesStartsFilterCorrectly()
    {
        $filterable = new SiteFilterableStub();

        /** @var Builder $builder */
        $builder = $filterable->filter([
            'name{starts}' => 'John',
            'project.name{starts}' => 'Rebuild',
        ]);

        $wheres = collect($builder->getQuery()->wheres)
            ->groupBy(function ($where) {
                return $where['type'];
            });

        $wheres->get('Basic')->each(function ($where) {
            static::assertArraySubset([
                'column' => 'name',
                'operator' => 'LIKE',
                'value' => 'John%',
            ], $where);
        });

        $wheres->get('Exists')->each(function ($where) {
            static::assertArraySubset([
                'column' => 'name',
                'operator' => 'LIKE',
                'value' => 'Rebuild%',
            ], $where['query']->wheres[1]);
        });
    }

    public function testItAppliesEndsFilterCorrectly()
    {
        $filterable = new SiteFilterableStub();

        /** @var Builder $builder */
        $builder = $filterable->filter([
            'name{ends}' => 'John',
            'project.name{ends}' => 'Rebuild',
        ]);

        $wheres = collect($builder->getQuery()->wheres)
            ->groupBy(function ($where) {
                return $where['type'];
            });

        $wheres->get('Basic')->each(function ($where) {
            static::assertArraySubset([
                'column' => 'name',
                'operator' => 'LIKE',
                'value' => '%John',
            ], $where);
        });

        $wheres->get('Exists')->each(function ($where) {
            static::assertArraySubset([
                'column' => 'name',
                'operator' => 'LIKE',
                'value' => '%Rebuild',
            ], $where['query']->wheres[1]);
        });
    }

    public function testItThrowsExceptionWithUnsetFilterField()
    {
        $this->expectException(FilterFieldNotFoundException::class);

        $filterable = new SiteFilterableStub();

        $filterable->filter(['middleName{ends}' => 'John']);
    }

    public function testItThrowsExceptionWithUndefinedFilter()
    {
        $this->expectException(UndefinedFilterException::class);

        $filterable = new BadFilterableStub();

        $filterable->filter(['name' => 'Fail']);
    }
}
