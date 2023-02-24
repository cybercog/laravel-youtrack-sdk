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

namespace Cog\Tests\Laravel\YouTrack\Unit\Providers;

use Cog\Contracts\YouTrack\Rest\Client\Client as ClientInterface;
use Cog\Tests\Laravel\YouTrack\AbstractTestCase;
use Cog\YouTrack\Rest\Client\YouTrackClient;

final class YouTrackServiceProviderTest extends AbstractTestCase
{
    /** @test */
    public function it_can_instantiate_youtrack_client_from_container(): void
    {
        $client = $this->app->make(ClientInterface::class);

        $this->assertInstanceOf(YouTrackClient::class, $client);
    }
}
