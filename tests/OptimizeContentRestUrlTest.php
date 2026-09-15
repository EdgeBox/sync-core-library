<?php

declare(strict_types=1);

namespace EdgeBox\SyncCore\Tests;

use EdgeBox\SyncCore\Exception\InternalContentSyncError;
use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use EdgeBox\SyncCore\Tests\Support\TestApplication;
use EdgeBox\SyncCore\V2\Raw\Model\SiteRestUrls;
use EdgeBox\SyncCore\V2\SyncCore;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class OptimizeContentRestUrlTest extends TestCase
{
    public function testTheSetterExistsAfterRegeneration(): void
    {
        $this->assertTrue(method_exists(SiteRestUrls::class, 'setOptimizeContent'), 'the regenerated model must carry setOptimizeContent()');
    }

    public function testAReferenceIsRegisteredWhenTheApplicationSuppliesOne(): void
    {
        $application = new TestApplication();
        $application->siteRestReferences[IApplicationInterface::REST_ACTION_OPTIMIZE_CONTENT] = '/aim/optimize-content?_format=json';

        $urls = $this->buildRestUrls($application);

        $this->assertSame('[site.baseUrl]/aim/optimize-content?_format=json', $urls->getOptimizeContent());
    }

    public function testNothingIsRegisteredWhenTheApplicationReturnsNull(): void
    {
        $application = new TestApplication();
        // No optimize-content reference supplied: the application returns NULL.

        $urls = $this->buildRestUrls($application);

        $this->assertNull($urls->getOptimizeContent());
    }

    public function testAMalformedReferenceRaisesAnError(): void
    {
        $application = new TestApplication();
        $application->siteRestReferences[IApplicationInterface::REST_ACTION_OPTIMIZE_CONTENT] = 'aim/optimize-content';

        $this->expectException(InternalContentSyncError::class);

        $this->buildRestUrls($application);
    }

    private function buildRestUrls(TestApplication $application): SiteRestUrls
    {
        $core = new SyncCore($application, 'https://core.example.com/sync-core');
        $method = new \ReflectionMethod($core, 'getRestUrls');
        $method->setAccessible(true);

        return $method->invoke($core);
    }
}
