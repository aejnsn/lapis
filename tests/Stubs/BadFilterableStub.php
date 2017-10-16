<?php

namespace Aejnsn\Lapis\Tests\Stubs;

use Aejnsn\Lapis\Filterable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BadFilterableStub
 *
 * @package Aejnsn\Lapis\Tests\Stubs
 */
class BadFilterableStub extends Model
{
    use Filterable;

    // $filter intentionally left undefined.
}
