<?php

declare(strict_types=1);

namespace EdgeBox\SyncCore\Tests;

use EdgeBox\SyncCore\Interfaces\Embed\IEmbedService;
use EdgeBox\SyncCore\Interfaces\Governance\IGovernanceService;
use EdgeBox\SyncCore\Interfaces\Governance\IOptimizeContentRequest;
use EdgeBox\SyncCore\V2\Governance\GovernanceService;
use EdgeBox\SyncCore\V2\SyncCore;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * A class that implements one of these interfaces for 4.x keeps working on
 * every 4.x release, so each declares the methods it declared in 4.5.0. What
 * the 4.x line added since lives on the concrete classes, and the accessors
 * that hand those classes out declare them, so that code reaching them
 * through SyncCore sees the added methods.
 *
 * @internal
 */
final class InterfaceCompatibilityTest extends TestCase
{
    /**
     * @return array<string, array{class-string, string[]}>
     */
    public static function interfacesAndTheirMethods(): array
    {
        return [
            'optimize-content request' => [IOptimizeContentRequest::class, ['getOptimizationId', 'getContentItemKey', 'getTargetLocale', 'getOptimizationTypeKeys', 'getPage', 'getIssues', 'getRecommendations', 'wasTruncated', 'accept', 'refuse']],
            'embed service' => [IEmbedService::class, ['registerSite', 'siteRegistered', 'siteSettings', 'pullDashboard', 'entityStatus', 'optimize', 'updateStatusBox', 'migrate', 'flowForm', 'syndicationDashboard', 'governanceContentInventory', 'pageFigures']],
            'governance service' => [IGovernanceService::class, ['parseOptimizeContentRequest', 'postExternalDraft', 'getContentItemByKey', 'getTaxonomyTermByKey']],
        ];
    }

    /**
     * @param class-string $interface
     * @param string[]     $methods
     */
    #[DataProvider('interfacesAndTheirMethods')]
    public function testTheInterfaceDeclaresWhat450Declared(string $interface, array $methods): void
    {
        $declared = array_map(static fn (\ReflectionMethod $method) => $method->getName(), (new \ReflectionClass($interface))->getMethods());

        $this->assertSame($methods, $declared);
    }

    /**
     * @return array<string, array{class-string, string, string}>
     */
    public static function accessorsAndTheirClasses(): array
    {
        return [
            'governance service' => [SyncCore::class, 'getGovernanceService', 'GovernanceService'],
            'embed service' => [SyncCore::class, 'getEmbedService', 'EmbedService'],
            'optimize-content request' => [GovernanceService::class, 'parseOptimizeContentRequest', 'OptimizeContentRequest'],
        ];
    }

    /**
     * @param class-string $class
     */
    #[DataProvider('accessorsAndTheirClasses')]
    public function testTheAccessorDeclaresTheConcreteClass(string $class, string $method, string $returned): void
    {
        $doc = (string) (new \ReflectionMethod($class, $method))->getDocComment();

        $this->assertMatchesRegularExpression('/@return '.$returned.'\s/', $doc);
    }
}
