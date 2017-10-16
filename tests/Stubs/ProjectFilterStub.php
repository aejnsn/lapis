<?php

namespace Aejnsn\Lapis\Tests\Stubs;

use Aejnsn\Lapis\Filter;

/**
 * Class ProjectFilterStub
 *
 * @package Aejnsn\Lapis\Tests\Stubs
 */
class ProjectFilterStub extends Filter
{
    /**
     * An array of fields on which filters can be applied.
     *
     * @var array
     */
    protected $filterableFields = [
        'name',
        'createdAt',
    ];
}
