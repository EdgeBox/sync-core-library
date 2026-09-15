<?php

declare(strict_types=1);

namespace EdgeBox\SyncCore\Tests;

use EdgeBox\SyncCore\Interfaces\Aim\ActingUser;
use EdgeBox\SyncCore\Tests\Support\TestApplication;
use EdgeBox\SyncCore\V2\SyncCore;
use Firebase\JWT\JWT;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class CreateJwtTest extends TestCase
{
    public function testExistingCallersMintTodaysPayload(): void
    {
        $core = $this->core();

        $payload = $this->decode($core->createJwt('content'));

        $this->assertSame(['exp', 'provider', 'scopes', 'type', 'uuid'], $this->sortedKeys($payload));
        $this->assertArrayNotHasKey('user', $payload);
        $this->assertSame(['content'], $payload['scopes']);
        $this->assertSame('site', $payload['type']);
    }

    public function testConfigurationMintsBothBaseScopes(): void
    {
        $core = $this->core();

        $payload = $this->decode($core->createJwt('configuration'));

        $this->assertSame(['configuration', 'content'], $payload['scopes']);
    }

    public function testAuthorizedScopesAndPersonAreAdded(): void
    {
        $core = $this->core();

        $payload = $this->decode($core->createJwt('content', 'jwt-header', ['issue:own:write'], ['name' => 'Lena', 'email' => 'lena@example.com']));

        $this->assertSame(['content', 'issue:own:write'], $payload['scopes']);
        $this->assertSame('Lena', $payload['user']['name']);
        $this->assertSame('lena@example.com', $payload['user']['email']);
    }

    public function testConfigurationWithAnAuthorizedScope(): void
    {
        $core = $this->core();

        $payload = $this->decode($core->createJwt('configuration', 'jwt-header', ['issue:own:write']));

        $this->assertSame(['configuration', 'content', 'issue:own:write'], $payload['scopes']);
    }

    public function testAScopeOutsideTheAllowlistThrowsBeforeSigning(): void
    {
        $core = $this->core();

        $this->expectException(\InvalidArgumentException::class);
        $core->createJwt('content', 'jwt-header', ['issue:all:write']);
    }

    public function testAnEmptyUserMintsNoClaim(): void
    {
        $core = $this->core();

        $payload = $this->decode($core->createJwt('content', 'jwt-header', [], []));

        $this->assertArrayNotHasKey('user', $payload);
    }

    public function testActingUserOmitsAnUnusableEmail(): void
    {
        $noAddress = (new ActingUser(['issue:own:read'], 'Lena', null))->toUserClaim();
        $this->assertSame(['name' => 'Lena'], $noAddress);

        $overLong = (new ActingUser(['issue:own:read'], 'Lena', str_repeat('a', 45).'@example.com'))->toUserClaim();
        $this->assertArrayNotHasKey('email', $overLong);

        $noTld = (new ActingUser(['issue:own:read'], 'Lena', 'lena@localhost'))->toUserClaim();
        $this->assertArrayNotHasKey('email', $noTld);

        $valid = (new ActingUser(['issue:own:read'], 'Lena', 'lena@example.com'))->toUserClaim();
        $this->assertSame('lena@example.com', $valid['email']);

        $this->assertNull((new ActingUser([], null, ''))->toUserClaim());
    }

    public function testAnActingUserMintsAUserScopedToken(): void
    {
        $core = $this->core();
        $acting = new ActingUser(['issue:own:write'], 'Lena', 'lena@example.com');

        $payload = $this->decode($core->createJwt('content', 'jwt-header', $acting->getScopes(), $acting->toUserClaim()));

        $this->assertSame(['content', 'issue:own:write'], $payload['scopes']);
        $this->assertSame('lena@example.com', $payload['user']['email']);
    }

    private function core(): SyncCore
    {
        return new SyncCore(new TestApplication(), 'https://core.example.com/sync-core');
    }

    /**
     * @return array
     */
    private function decode(string $jwt): array
    {
        return json_decode(json_encode(JWT::decode($jwt, 'test-secret', ['HS256'])), true);
    }

    /**
     * @return string[]
     */
    private function sortedKeys(array $payload): array
    {
        $keys = array_keys($payload);
        sort($keys);

        return $keys;
    }
}
