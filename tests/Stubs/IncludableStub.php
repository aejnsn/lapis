<?php

namespace Aejnsn\Lapis\Tests\Stubs;

use Aejnsn\Lapis\Includable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class IncludableStub
 *
 * @package Aejnsn\Lapis\Tests\Stubs
 */
class IncludableStub extends Model
{
    use Includable;
}
