<?php

declare(strict_types=1);

namespace EdgeBox\SyncCore\Tests;

use EdgeBox\SyncCore\Interfaces\Governance\ActingUser;
use EdgeBox\SyncCore\Tests\Support\TestApplication;
use EdgeBox\SyncCore\V2\Embed\Embed;
use EdgeBox\SyncCore\V2\Embed\EmbedService;
use EdgeBox\SyncCore\V2\SyncCore;
use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class GovernanceContentInventoryEmbedTest extends TestCase
{
    public function testTheUrlResolvesToTheContentInventoryPath(): void
    {
        $embed = (new EmbedService($this->core()))->governanceContentInventory([]);
        $embed->run();

        $this->assertStringEndsWith('/brand-presence/content-inventory', $this->url($embed));
    }

    public function testWithoutAnActingUserItMintsTheSitesOwnToken(): void
    {
        $embed = (new EmbedService($this->core()))->governanceContentInventory([]);
        $embed->run();

        $payload = $this->jwtOf($embed);
        $this->assertArrayNotHasKey('user', $payload);
        $this->assertSame(['content'], $payload['scopes']);
    }

    public function testWithAnActingUserItMintsThatPersonsToken(): void
    {
        $acting = new ActingUser(['content-optimization:own:read'], 'Lena', 'lena@example.com');
        $embed = (new EmbedService($this->core()))->governanceContentInventory([], $acting);
        $embed->run();

        $payload = $this->jwtOf($embed);
        $this->assertSame(['content', 'content-optimization:own:read'], $payload['scopes']);
        $this->assertSame('lena@example.com', $payload['user']['email']);
    }

    public function testTheTenExistingEmbedsCarryNoPersonClaim(): void
    {
        $service = (new EmbedService($this->core()));
        $embeds = [
            $service->registerSite([]),
            $service->siteRegistered([]),
            $service->siteSettings([]),
            $service->pullDashboard([]),
            $service->entityStatus([]),
            $service->optimize([]),
            $service->updateStatusBox([]),
            $service->migrate(['pools' => [], 'flows' => [], 'settings' => []]),
            $service->flowForm([]),
            $service->syndicationDashboard([]),
        ];

        foreach ($embeds as $embed) {
            $embed->run();
            $config = $this->protectedValue($embed, 'config');
            if (isset($config['jwt'])) {
                $payload = json_decode(json_encode(JWT::decode($config['jwt'], 'test-secret', ['HS256'])), true);
                $keys = array_keys($payload);
                sort($keys);
                $this->assertSame(['exp', 'provider', 'scopes', 'type', 'uuid'], $keys, get_class($embed).' must mint the same claim set it minted on 4.3.0');
                $this->assertSame('site', $payload['type']);
                $this->assertSame('jwt-header', $payload['provider']);
                $this->assertContains('content', $payload['scopes']);
            }
        }
    }

    private function core(): SyncCore
    {
        // A permissive client so any incidental request an embed makes resolves.
        $handler = HandlerStack::create(new MockHandler(array_fill(0, 50, new Response(200, [], '{}'))));

        return new SyncCore(new TestApplication(new Client(['handler' => $handler])), 'https://core.example.com/sync-core');
    }

    private function url(Embed $embed): string
    {
        return $this->protectedValue($embed, 'url');
    }

    /**
     * @return array
     */
    private function jwtOf(Embed $embed): array
    {
        $config = $this->protectedValue($embed, 'config');

        return json_decode(json_encode(JWT::decode($config['jwt'], 'test-secret', ['HS256'])), true);
    }

    /**
     * @return mixed
     */
    private function protectedValue(object $object, string $property)
    {
        $reflection = new \ReflectionProperty($object, $property);
        $reflection->setAccessible(true);

        return $reflection->getValue($object);
    }
}
