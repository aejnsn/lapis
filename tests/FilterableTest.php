<?php

namespace Aejnsn\Lapis\Tests;

use Orchestra\Testbench\TestCase;
use Illuminate\Database\Eloquent\Builder;
use Aejnsn\Lapis\Tests\Stubs\BadFilterableStub;
use Aejnsn\Lapis\Tests\Stubs\SiteFilterableStub;
use Aejnsn\Lapis\Exceptions\UndefinedFilterException;
use Aejnsn\Lapis\Exceptions\FilterFieldNotFoundException;

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
            static::assertArraySubset([
                'operator' => '='
            ], $where);
        });

        $wheres->get('Exists')->each(function ($where) {
            static::assertArraySubset([
                'operator' => '='
            ], $where['query']->wheres[0]);
        });
    }

    /**
     * @param $filter
     * @param $expects
     * @dataProvider filtersProvider
     */
    public function testItAppliesFiltersCorrectly($filter, $expects)
    {
        $filterable = new SiteFilterableStub();

        /** @var Builder $builder */
        $builder = $filterable->filter($filter);

        $wheres = collect($builder->getQuery()->wheres)
            ->groupBy(function ($where) {
                return $where['type'];
            });

        $wheres->get('Basic')->each(function ($where) use ($expects) {
            static::assertArraySubset($expects['basic'], $where);
        });

        $wheres->get('Exists')->each(function ($where) use ($expects) {
            static::assertArraySubset($expects['exists'],
                $where['query']->wheres[1]
            );
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

    /**
     * Data provider for verifying filters are applied correctly
     * @return array
     */
    public function filtersProvider()
    {
        return [
            'ItAppliesEqualFilterCorrectly ' => [
                'filter' => [
                    'temperature{eq}' => '100',
                    'project.name{eq}' => 'Rebuild',
                ],
                'expects' => [
                    'basic' => [
                        'column' => 'temperature',
                        'operator' => '=',
                        'value' => '100',
                    ],
                    'exists' => [
                        'column' => 'name',
                        'operator' => '=',
                        'value' => 'Rebuild',
                    ]
                ]
            ],

            'ItAppliesLessThanFilterCorrectly' => [
                'filter' => [
                    'temperature{lt}' => '100',
                    'project.createdAt{lt}' => '2017-11-01',
                ],
                'expects' => [
                    'basic' => [
                        'column' => 'temperature',
                        'operator' => '<',
                        'value' => '100',
                    ],
                    'exists' => [
                        'column' => 'created_at',
                        'operator' => '<',
                        'value' => '2017-11-01',
                    ]
                ]
            ],

            'ItAppliesGreaterThanFilterCorrectly' => [
                'filter' => [
                    'temperature{gt}' => '100',
                    'project.createdAt{gt}' => '2017-11-01',
                ],
                'expects' => [
                    'basic' => [
                        'column' => 'temperature',
                        'operator' => '>',
                        'value' => '100',
                    ],
                    'exists' => [
                        'column' => 'created_at',
                        'operator' => '>',
                        'value' => '2017-11-01',
                    ]
                ]
            ],

            'ItAppliesLessThanOrEqualFilterCorrectly' => [
                'filter' => [
                    'temperature{lte}' => '100',
                    'project.createdAt{lte}' => '2017-11-01',
                ],
                'expects' => [
                    'basic' => [
                        'column' => 'temperature',
                        'operator' => '<=',
                        'value' => '100',
                    ],
                    'exists' => [
                        'column' => 'created_at',
                        'operator' => '<=',
                        'value' => '2017-11-01',
                    ]
                ]
            ],

            'ItAppliesGreaterThanOrEqualFilterCorrectly' => [
                'filter' => [
                    'temperature{gte}' => '100',
                    'project.createdAt{gte}' => '2017-11-01',
                ],
                'expects' => [
                    'basic' => [
                        'column' => 'temperature',
                        'operator' => '>=',
                        'value' => '100',
                    ],
                    'exists' => [
                        'column' => 'created_at',
                        'operator' => '>=',
                        'value' => '2017-11-01',
                    ]
                ]
            ],

            'ItAppliesLikeFilterCorrectly' => [
                'filter' => [
                    'name{like}' => 'John',
                    'project.name{like}' => 'Rebuild',
                ],
                'expects' => [
                    'basic' => [
                        'column' => 'name',
                        'operator' => 'LIKE',
                        'value' => '%John%',
                    ],
                    'exists' => [
                        'column' => 'name',
                        'operator' => 'LIKE',
                        'value' => '%Rebuild%',
                    ]
                ]
            ],

            'ItAppliesNotLikeFilterCorrectly' => [
                'filter' => [
                    'name{nlike}' => 'John',
                    'project.name{nlike}' => 'Rebuild',
                ],
                'expects' => [
                    'basic' => [
                        'column' => 'name',
                        'operator' => 'NOT LIKE',
                        'value' => '%John%',
                    ],
                    'exists' => [
                        'column' => 'name',
                        'operator' => 'NOT LIKE',
                        'value' => '%Rebuild%',
                    ]
                ]
            ],

            'ItAppliesStartsFilterCorrectly' => [
                'filter' => [
                    'name{starts}' => 'John',
                    'project.name{starts}' => 'Rebuild',
                ],
                'expects' => [
                    'basic' => [
                        'column' => 'name',
                        'operator' => 'LIKE',
                        'value' => 'John%',
                    ],
                    'exists' => [
                        'column' => 'name',
                        'operator' => 'LIKE',
                        'value' => 'Rebuild%',
                    ]
                ]
            ],

            'ItAppliesEndsFilterCorrectly' => [
                'filter' => [
                    'name{ends}' => 'John',
                    'project.name{ends}' => 'Rebuild',
                ],
                'expects' => [
                    'basic' => [
                        'column' => 'name',
                        'operator' => 'LIKE',
                        'value' => '%John',
                    ],
                    'exists' => [
                        'column' => 'name',
                        'operator' => 'LIKE',
                        'value' => '%Rebuild',
                    ]
                ]
            ]
        ];
    }
}
