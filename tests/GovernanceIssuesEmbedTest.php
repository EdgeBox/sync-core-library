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
final class GovernanceIssuesEmbedTest extends TestCase
{
    public function testTheUrlResolvesToTheIssuesPath(): void
    {
        $embed = (new EmbedService($this->core()))->governanceIssues([]);
        $embed->run();

        $this->assertStringEndsWith('/brand-presence/issues', $this->url($embed));
    }

    public function testItIsNotTheContentInventoryPath(): void
    {
        $embed = (new EmbedService($this->core()))->governanceIssues([]);
        $embed->run();

        $this->assertStringNotContainsString('/brand-presence/content-inventory', $this->url($embed));
    }

    public function testWithoutAnActingUserItMintsTheSitesOwnToken(): void
    {
        $embed = (new EmbedService($this->core()))->governanceIssues([]);
        $embed->run();

        $payload = $this->jwtOf($embed);
        $this->assertSame('site', $payload['type']);
        $this->assertArrayNotHasKey('user', $payload);
        $this->assertSame(['content'], $payload['scopes']);
    }

    public function testWithAnActingUserItMintsThatPersonsToken(): void
    {
        $acting = new ActingUser(['issue:own:read', 'issue:own:write'], 'Lena', 'lena@example.com');
        $embed = (new EmbedService($this->core()))->governanceIssues([], $acting);
        $embed->run();

        $payload = $this->jwtOf($embed);
        $this->assertSame('site', $payload['type']);
        $this->assertSame(['content', 'issue:own:read', 'issue:own:write'], $payload['scopes']);
        $this->assertSame('lena@example.com', $payload['user']['email']);
    }

    public function testAPersonWithoutTheWriteScopeCarriesOnlyWhatTheySiteAuthorized(): void
    {
        $acting = new ActingUser(['issue:own:read'], 'Sam', 'sam@example.com');
        $embed = (new EmbedService($this->core()))->governanceIssues([], $acting);
        $embed->run();

        $payload = $this->jwtOf($embed);
        $this->assertSame(['content', 'issue:own:read'], $payload['scopes']);
        $this->assertNotContains('issue:own:write', $payload['scopes']);
    }

    public function testTheOptionsTheCallerNamesTravelIntoTheFrame(): void
    {
        $embed = (new EmbedService($this->core()))->governanceIssues(['query' => ['status' => 'open']]);
        $html = $embed->run()->getRenderedHtml();

        // The options are handed to the frame as the message the screen reads
        // its own query out of, which is how a link opens the list on a filter.
        $this->assertStringContainsString('"query":{"status":"open"}', $html);
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
