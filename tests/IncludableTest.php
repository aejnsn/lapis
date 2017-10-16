<?php

namespace Aejnsn\Lapis\Tests;

use Orchestra\Testbench\TestCase;
use Illuminate\Database\Eloquent\Builder;
use Aejnsn\Lapis\Tests\Stubs\IncludableStub;

/**
 * Class IncludableTest
 *
 * @package Aejnsn\Lapis\Tests
 */
class IncludableTest extends TestCase
{
    public function testItAppliesIncludeScopeWithIntendedEagerLoads()
    {
        $includable = new IncludableStub;

        /** @var Builder $builder */
        $builder = $includable->include([
            'documents.location',
            'location',
            'project.client.users.role',
            'project.manager',
        ]);

        collect([
            'documents',
            'documents.location',
            'location',
            'project',
            'project.client',
            'project.client.users',
            'project.client.users.role',
            'project.manager',
        ])->each(function ($eagerLoad) use ($builder) {
            static::assertArrayHasKey($eagerLoad, $builder->getEagerLoads());
        });
    }
}
