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

namespace Cog\Tests\Laravel\YouTrack;

use Cog\Laravel\YouTrack\Providers\YouTrackServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

/**
 * Class TestCase.
 *
 * @package Cog\Tests\Laravel\YouTrack
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
            YouTrackServiceProvider::class,
        ];
    }
}
