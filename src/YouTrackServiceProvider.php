<?php

/*
 * This file is part of Laravel YouTrack SDK.
 *
 * (c) Anton Komarev <anton@komarev.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Cog\Laravel\YouTrack;

use Cog\Contracts\YouTrack\Rest\Authorizer\Authorizer as AuthorizerContract;
use Cog\Contracts\YouTrack\Rest\Client\Client as ClientContract;
use Cog\YouTrack\Rest\Authenticator\CookieAuthenticator;
use Cog\YouTrack\Rest\Client\YouTrackClient;
use Cog\YouTrack\Rest\HttpClient\GuzzleHttpClient;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Contracts\Config\Repository as ConfigContract;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

final class YouTrackServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerBindings();
    }

    public function boot(): void
    {
        $this->bootConfig();
    }

    private function registerBindings(): void
    {
        $this->app->bind(ClientContract::class, function () {
            $config = $this->app->make(ConfigContract::class);

            $httpClient = new GuzzleHttpClient(new HttpClient([
                'base_uri' => $config->get('youtrack.base_uri'),
            ]));

            return new YouTrackClient($httpClient, $this->resolveAuthorizerDriver($config));
        });
    }

    private function bootConfig(): void
    {
        $source = realpath(__DIR__ . '/../config/youtrack.php');

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('youtrack.php')], 'youtrack-config');
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('youtrack');
        }

        $this->mergeConfigFrom($source, 'youtrack');
    }

    private function resolveAuthorizerDriver(ConfigContract $config): AuthorizerContract
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
