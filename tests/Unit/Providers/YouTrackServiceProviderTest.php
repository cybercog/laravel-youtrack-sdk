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

namespace Cog\Laravel\YouTrack\Tests\Unit\Providers;

use Cog\YouTrack\Rest\Client\Contracts\Client as ClientContract;
use Cog\Laravel\YouTrack\Tests\TestCase;
use Cog\YouTrack\Rest\Client\YouTrackClient;

/**
 * Class YouTrackServiceProviderTest.
 *
 * @package Cog\Laravel\YouTrack\Tests\Unit\Providers
 */
class YouTrackServiceProviderTest extends TestCase
{
    /** @test */
    public function it_can_instantiate_youtrack_client_from_container()
    {
        $client = $this->app->make(ClientContract::class);

        $this->assertInstanceOf(YouTrackClient::class, $client);
    }
}
