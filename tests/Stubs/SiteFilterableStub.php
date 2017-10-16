<?php

namespace Aejnsn\Lapis\Tests\Stubs;

use Aejnsn\Lapis\Filter;
use Aejnsn\Lapis\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class SiteFilterableStub
 *
 * @package Aejnsn\Lapis\Tests\Stubs
 */
class SiteFilterableStub extends Model
{
    use Filterable;

    /**
     * An instance of a Filter defined for this model.
     *
     * @var Filter
     */
    protected $filter = SiteFilterStub::class;

    /**
     * A Site belongs to a Project.
     *
     * @return BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(ProjectFilterableStub::class);
    }
}
