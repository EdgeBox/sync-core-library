<?php

declare(strict_types=1);

namespace EdgeBox\SyncCore\Tests;

use EdgeBox\SyncCore\Interfaces\Embed\IEmbedService;
use EdgeBox\SyncCore\Interfaces\Embed\PageFiguresBoxParams;
use EdgeBox\SyncCore\V2\Embed\PageFiguresEmbed;
use EdgeBox\SyncCore\V2\Raw\Api\DefaultApi;
use EdgeBox\SyncCore\V2\SyncCore;
use PHPUnit\Framework\TestCase;

/**
 * The package ships a single PSR-4 root, "EdgeBox\SyncCore\" mapped to src/.
 * A site that installs the package reaches the library through it and through
 * nothing else, so both the hand-written entry point and the generated client
 * have to resolve under that one prefix.
 *
 * @internal
 */
final class AutoloadTest extends TestCase
{
    public function testTheEntryPointResolves(): void
    {
        $this->assertTrue(class_exists(SyncCore::class));
    }

    public function testTheGeneratedApiResolves(): void
    {
        $this->assertTrue(class_exists(DefaultApi::class));
    }

    public function testThePageFiguresBoxResolves(): void
    {
        $this->assertTrue(class_exists(PageFiguresBoxParams::class));
        $this->assertTrue(class_exists(PageFiguresEmbed::class));
        $this->assertSame('box.page-figures', IEmbedService::BOX_PAGE_FIGURES);
    }
}
