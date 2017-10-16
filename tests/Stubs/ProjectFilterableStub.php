<?php

namespace Aejnsn\Lapis\Tests\Stubs;

use Aejnsn\Lapis\Filter;
use Aejnsn\Lapis\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class ProjectFilterableStub
 *
 * @package Aejnsn\Lapis\Tests\Stubs
 */
class ProjectFilterableStub extends Model
{
    use Filterable;

    /**
     * An instance of a Filter defined for this model.
     *
     * @var Filter
     */
    protected $filter = ProjectFilterStub::class;

    /**
     * A Project has many Sites.
     *
     * @return HasMany
     */
    public function sites()
    {
        return $this->hasMany(SiteFilterableStub::class);
    }
}
