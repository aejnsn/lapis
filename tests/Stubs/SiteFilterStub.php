<?php

namespace Aejnsn\Lapis\Tests\Stubs;

use Aejnsn\Lapis\Filter;

/**
 * Class SiteFilterStub
 *
 * @package Aejnsn\Lapis\Tests\Stubs
 */
class SiteFilterStub extends Filter
{
    /**
     * An array of fields on which filters can be applied.
     *
     * @var array
     */
    protected $filterableFields = [
        'name',
        'temperature',
    ];
}
