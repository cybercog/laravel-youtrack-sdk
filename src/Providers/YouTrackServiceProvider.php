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

namespace Cog\Laravel\YouTrack\Providers;

use Cog\YouTrack\Rest\Authenticator\CookieAuthenticator;
use Cog\YouTrack\Rest\Authorizer\Contracts\Authorizer as AuthorizerContract;
use Cog\YouTrack\Rest\Client\Contracts\Client as ClientContract;
use Cog\YouTrack\Rest\Client\YouTrackClient;
use Cog\YouTrack\Rest\HttpClient\GuzzleHttpClient;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Contracts\Config\Repository as ConfigContract;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application as LaravelApplication;
use Laravel\Lumen\Application as LumenApplication;

/**
 * Class YouTrackServiceProvider.
 *
 * @package Cog\Laravel\YouTrack\Providers
 */
class YouTrackServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->bootConfig();
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(ClientContract::class, function () {
            $config = $this->app->make(ConfigContract::class);

            $httpClient = new GuzzleHttpClient(new HttpClient([
                'base_uri' => $config->get('youtrack.base_uri'),
            ]));

            return new YouTrackClient($httpClient, $this->resolveAuthorizer($config));
        });
    }

    /**
     * Boot Laravel or Lumen config.
     *
     * @return void
     */
    protected function bootConfig(): void
    {
        $source = realpath(__DIR__ . '/../../config/youtrack.php');

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('youtrack.php')], 'config');
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('youtrack');
        }

        $this->mergeConfigFrom($source, 'youtrack');
    }

    /**
     * Resolve Authorizer driver.
     *
     * @param \Illuminate\Contracts\Config\Repository $config
     * @return \Cog\YouTrack\Rest\Authorizer\Contracts\Authorizer
     */
    protected function resolveAuthorizer(ConfigContract $config): AuthorizerContract
    {
        $authorizer = $config->get('youtrack.authorizer');

        $options = $config->get('youtrack.authorizers.' . $authorizer);
        if ($authorizer == 'cookie') {
            return new $options['driver'](
                new CookieAuthenticator($options['username'], $options['password'])
            );
        }

        return new $options['driver']($options['token']);
    }
}
