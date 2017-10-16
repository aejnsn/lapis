<?php

namespace Aejnsn\Lapis\Tests;

use Aejnsn\Lapis\LapisServiceProvider;
use GrahamCampbell\TestBench\AbstractPackageTestCase;
use GrahamCampbell\TestBenchCore\ServiceProviderTrait;

/**
 * Class TestCase
 *
 * @package Aejnsn\Lapis\Tests
 */
class TestCase extends AbstractPackageTestCase
{
    use ServiceProviderTrait;

    /**
     * Get the service provider class.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return string
     */
    protected function getServiceProviderClass($app)
    {
        return LapisServiceProvider::class;
    }
}
