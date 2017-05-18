<?php

declare(strict_types=1);

/*
 * This file is part of Laravel YouTrack SDK.
 *
 * (c) Anton Komarev <a.komarev@cybercog.su>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cog\Laravel\YouTrack\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use ReflectionClass;

/**
 * Class TestCase.
 *
 * @package Cog\Laravel\YouTrack\Rest\Tests
 */
abstract class TestCase extends Orchestra
{
    /**
     * Load package service provider.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Cog\Laravel\YouTrack\Providers\YouTrackServiceProvider::class,
        ];
    }

    /**
     * Force set private property of the object.
     *
     * @param object $class
     * @param string $property
     * @param mixed $value
     * @return void
     */
    protected function setPrivateProperty($class, string $property, $value)
    {
        $reflector = new ReflectionClass($class);
        $property = $reflector->getProperty($property);
        $property->setAccessible(true);
        $property->setValue($class, $value);
    }
}
