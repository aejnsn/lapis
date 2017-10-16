<?php

namespace Aejnsn\Lapis\Tests;

use Illuminate\Http\Request;
use Aejnsn\Lapis\Exceptions\InvalidFilterQueryException;

/**
 * Class RequestMixinTest
 *
 * @package Aejnsn\Lapis\Tests
 */
class RequestMixinTest extends TestCase
{
    public function testIncludesMacroIsProvided()
    {
        static::assertTrue(Request::hasMacro('includes'));
    }

    public function testFiltersMacroIsProvided()
    {
        static::assertTrue(Request::hasMacro('filters'));
    }

    public function testIncludesMacroCorrectlyReturnsIncludesArray()
    {
        $request = new Request([
            // Inconsistent spacing is intentional.
            'include' => 'documents.location,location, project.client.users.role, project.manager'
        ]);

        static::assertEquals([
            'documents.location',
            'location',
            'project.client.users.role',
            'project.manager',
        ], $request->includes());
    }

    public function testIncludesMacroCorrectlyReturnsBlankIncludesArray()
    {
        $request = new Request([]);

        static::assertEquals([], $request->includes());
    }

    public function testFiltersMacroCorrectlyReturnsFiltersArray()
    {
        $request = new Request([
            'filter'=> [
                'name{eq}' => 'Rick James',
                'description{like}' => 'Enjoy yourself',
            ]
        ]);

        static::assertEquals([
            'name{eq}' => 'Rick James',
            'description{like}' => 'Enjoy yourself',
        ], $request->filters());
    }

    public function testFiltersMacroCorrectlyReturnsBlankFiltersArray()
    {
        $request = new Request([]);

        static::assertEquals([], $request->filters());
    }

    public function testFiltersMacroThrowsExceptionWithBadlyFormattedFilterParameter()
    {
        $this->expectException(InvalidFilterQueryException::class);

        $request = new Request(['filter' => 'should_be_an_array']);
        $request->filters();
    }
}
