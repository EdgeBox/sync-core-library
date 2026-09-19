<?php

declare(strict_types=1);

namespace EdgeBox\SyncCore\Tests;

use EdgeBox\SyncCore\Tests\Support\EmbedMarkup;
use EdgeBox\SyncCore\V2\Embed\EmbedService;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class EmbedFrameSizeTest extends TestCase
{
    public function testEveryReleasedEmbedRendersTheMarkupItRendered(): void
    {
        $recorded = json_decode((string) file_get_contents(EmbedMarkup::recordingPath()), true);
        $rendered = EmbedMarkup::releasedMarkup(EmbedMarkup::core());

        $this->assertSame(array_keys($recorded), array_keys($rendered));

        foreach ($recorded as $class => $html) {
            $this->assertSame($html, $rendered[$class], $class.' renders markup the released library did not render');
        }
    }

    public function testAPageStatesItsOwnMinimumHeightAndResizes(): void
    {
        $html = $this->markupForSize('page');

        $this->assertStringContainsString('min-width: 100%; width: 1px;', $html);
        $this->assertStringContainsString('min-height: 200px;', $html);
        $this->assertStringContainsString('autoResize: true', $html);
        $this->assertStringContainsString('class="content-sync-embed size-page"', $html);
    }

    public function testALineKeepsItsFixedGeometryAndLoadsWhenScrolledTo(): void
    {
        $html = $this->markupForSize('line');

        $this->assertStringContainsString('width: 470px;', $html);
        $this->assertStringContainsString('height: 32px; max-height: 40px;', $html);
        $this->assertStringContainsString('border-radius: 5px;', $html);
        $this->assertStringContainsString('autoResize: false', $html);
        $this->assertStringContainsString('src="" frameborder="0"', $html);
        $this->assertStringContainsString('jQuery(window).scroll(checkIfVisible);', $html);
    }

    public function testABoxTakesItsContainersWidthAndItsDocumentsHeight(): void
    {
        $html = $this->markupForSize('box');

        $this->assertStringContainsString('min-width: 100%; width: 1px;', $html);
        $this->assertStringContainsString('autoResize: true', $html);

        // The shared floor is the only height the frame states, so it is as
        // tall as the document reports and the resizer keeps it there.
        $this->assertStringContainsString('min-height: 32px;', $html);
        $this->assertStringNotContainsString('min-height: 200px', $html);
        $this->assertStringNotContainsString('max-height: 40px', $html);
        $this->assertStringNotContainsString('width: 470px', $html);
    }

    public function testABoxLoadsWithThePageRatherThanWhenScrolledTo(): void
    {
        $html = $this->markupForSize('box');

        $this->assertStringContainsString('class="content-sync-embed size-box"', $html);
        $this->assertStringContainsString('src="https://embed.content-sync.io/box/update-status"', $html);
        $this->assertStringNotContainsString('jQuery(window).scroll(checkIfVisible);', $html);
        $this->assertStringNotContainsString('border-radius: 5px;', $html);
    }

    public function testASizeNobodyKnowsStillRendersTheLineGeometry(): void
    {
        $html = $this->markupForSize('something-else');

        $this->assertStringContainsString('width: 470px;', $html);
        $this->assertStringContainsString('height: 32px; max-height: 40px;', $html);
        $this->assertStringContainsString('autoResize: false', $html);
    }

    /**
     * The markup of one embed rendered at the given frame size.
     *
     * The size travels in the options, so any embed renders at any size; this
     * one is picked because it sets a size of its own and therefore proves the
     * caller's value is what decides.
     */
    private function markupForSize(string $size): string
    {
        $embed = (new EmbedService(EmbedMarkup::core()))->updateStatusBox(['embedSize' => $size]);

        return EmbedMarkup::of($embed);
    }
}
