<?php declare(strict_types=1);

namespace Aejnsn\Lapis;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

/**
 * Class LapisServiceProvider
 *
 * @package Aejnsn\Lapis
 */
class LapisServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Request::mixin(new RequestMixin);
    }
}
