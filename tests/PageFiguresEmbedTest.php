<?php

declare(strict_types=1);

namespace EdgeBox\SyncCore\Tests;

use EdgeBox\SyncCore\Helpers\EmbedResult;
use EdgeBox\SyncCore\Interfaces\Embed\IEmbedFeature;
use EdgeBox\SyncCore\Interfaces\Embed\PageFiguresBoxParams;
use EdgeBox\SyncCore\Tests\Support\EmbedMarkup;
use EdgeBox\SyncCore\Tests\Support\TestApplication;
use EdgeBox\SyncCore\V2\Embed\EmbedService;
use EdgeBox\SyncCore\V2\SyncCore;
use Firebase\JWT\JWT;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class PageFiguresEmbedTest extends TestCase
{
    /**
     * @var null|string
     */
    private $embedUrlBefore;

    protected function setUp(): void
    {
        $given = getenv('CONTENT_SYNC_CLOUD_EMBED_URL');
        $this->embedUrlBefore = false === $given ? null : $given;
    }

    protected function tearDown(): void
    {
        if (null === $this->embedUrlBefore) {
            putenv('CONTENT_SYNC_CLOUD_EMBED_URL');

            return;
        }

        putenv('CONTENT_SYNC_CLOUD_EMBED_URL='.$this->embedUrlBefore);
    }

    public function testOneCallWithOneNamedArrayRendersTheBox(): void
    {
        $embed = $this->pageFigures();

        $this->assertInstanceOf(IEmbedFeature::class, $embed);

        $result = $embed->run();

        $this->assertInstanceOf(EmbedResult::class, $result);
        $this->assertNotNull($result->getRenderedHtml());
        $this->assertStringContainsString('src="https://embed.content-sync.io/box/page-figures"', (string) $result->getRenderedHtml());
    }

    public function testTheBoxReceivesTheFiguresAndTheSizeTheLibraryDecides(): void
    {
        $html = EmbedMarkup::of($this->pageFigures());

        $this->assertSame([
            'embedSize' => 'box',
            'entityType' => 'article',
            'entityUuid' => 'f1b0c0de-0000-4000-8000-000000000001',
            'langcode' => 'en',
            'contentHealthPercent' => 84,
            'openIssueCount' => 3,
            'contentPriority' => 300,
            'citedInAnswersLast30Days' => 12,
            'summaryUpdated' => 1758240000,
            'tags' => [
                ['key' => 'pricing', 'name' => 'Pricing'],
            ],
        ], $this->messages($html)['options']);
    }

    public function testTheFrameIsABoxAndNoCallerCanAskForAnotherSize(): void
    {
        $html = EmbedMarkup::of($this->pageFigures());

        $this->assertSame('box', $this->messages($html)['options']['embedSize']);
        $this->assertStringContainsString('class="content-sync-embed size-box"', $html);
        $this->assertStringNotContainsString('width: 470px', $html);

        // The size is no figure of a page, so a caller naming it is passed
        // over and the frame is a box all the same.
        $figures = self::everyFigure();
        $figures['embedSize'] = 'line';

        $asked = EmbedMarkup::of($this->pageFigures(new PageFiguresBoxParams($figures)));

        $this->assertSame('box', $this->messages($asked)['options']['embedSize']);
        $this->assertStringContainsString('class="content-sync-embed size-box"', $asked);
        $this->assertStringNotContainsString('width: 470px', $asked);
    }

    public function testAByteThatIsNoTextLeavesTheScriptParsable(): void
    {
        // Any option of any embed could carry one, and an encoding that failed
        // would write nothing where the payload goes and stop every embed on
        // the page.
        $embed = (new EmbedService(EmbedMarkup::core()))->updateStatusBox([
            'embedSize' => 'box',
            'note' => "a value with \xC3\x28 in it",
        ]);

        $html = EmbedMarkup::of($embed);
        $options = $this->messages($html)['options'];

        $this->assertIsArray($options, 'the payload is JSON a parser reads');
        $this->assertArrayHasKey('note', $options);
        $this->assertStringNotContainsString('options: ,', $html);
    }

    public function testProseAPersonWroteCannotCloseTheScriptItTravelsIn(): void
    {
        $figures = self::everyFigure();
        $figures['tags'] = [
            ['key' => 'pricing', 'name' => 'Watch out: <!--<script> and </script> and <b>bold</b>.'],
            ['key' => 'onboarding', 'name' => '</script><img src=x>'],
        ];

        $html = EmbedMarkup::of($this->pageFigures(new PageFiguresBoxParams($figures)));

        $this->assertSame(1, preg_match('@^\s*options: (\{.*\}),$@m', $html, $matches));

        // Not one markup character of what a person wrote reaches the script
        // element, so nothing they write can take the browser out of it.
        $this->assertStringNotContainsString('<', $matches[1]);
        $this->assertStringContainsString('\\u003C', $matches[1]);
        $this->assertStringNotContainsString('<!--<script>', $html);

        // And the box still receives exactly what the site wrote.
        $options = $this->messages($html)['options'];
        $this->assertSame($figures['tags'][0]['name'], $options['tags'][0]['name']);
        $this->assertSame('</script><img src=x>', $options['tags'][1]['name']);
    }

    public function testTheFrameTakesItsContainersWidthAndItsDocumentsHeight(): void
    {
        $html = EmbedMarkup::of($this->pageFigures());

        $this->assertStringContainsString('min-width: 100%; width: 1px;', $html);
        $this->assertStringContainsString('min-height: 32px;', $html);
        $this->assertStringNotContainsString('min-height: 200px', $html);
        $this->assertStringNotContainsString('max-height: 40px', $html);
        $this->assertStringContainsString('autoResize: true', $html);
        $this->assertStringContainsString('class="content-sync-embed size-box"', $html);
    }

    public function testTheFrameIsMeasuredAgainEachTimeItIsUncovered(): void
    {
        $html = EmbedMarkup::of($this->pageFigures());

        // The watch starts once the resizer has been asked to attach, and it
        // watches this frame and no other.
        $this->assertStringContainsString("    }, \"#contentSyncEmbed-ID\");\n    remeasureOnUncover();\n  }", $html);
        $this->assertStringContainsString('var element = document.getElementById("contentSyncEmbed-ID");', $html);
        $this->assertStringContainsString('new IntersectionObserver(', $html);
        $this->assertStringContainsString('observer.observe(element);', $html);
        $this->assertStringContainsString('element.iFrameResizer.resize();', $html);

        // It asks again every 200 milliseconds, 25 times at most, until the
        // resizer has attached, and it stops once the frame has left the page.
        $this->assertStringContainsString('if(attempt<25) {', $html);
        $this->assertStringContainsString("remeasure(attempt+1);\n        }, 200);", $html);
        $this->assertStringContainsString("if(!element.isConnected) {\n        observer.disconnect();", $html);
    }

    public function testNoCallerCanTurnTheMeasuringOff(): void
    {
        $figures = self::everyFigure();
        $figures['remeasureOnUncover'] = false;
        $figures['remeasure_on_uncover'] = false;

        $html = EmbedMarkup::of($this->pageFigures(new PageFiguresBoxParams($figures)));

        $this->assertStringContainsString('remeasureOnUncover();', $html);
        $this->assertArrayNotHasKey('remeasureOnUncover', $this->messages($html)['options']);
        $this->assertArrayNotHasKey('remeasure_on_uncover', $this->messages($html)['options']);
    }

    public function testTheFrameCarriesTheSitesOwnTokenAndNoPersonsIdentity(): void
    {
        $rendered = (string) $this->pageFigures()->run()->getRenderedHtml();
        $config = $this->messages($rendered)['config'];

        $this->assertSame(['syncCoreDomain', 'baseUrl', 'featureFlags', 'jwt'], array_keys($config));

        $payload = json_decode(json_encode(JWT::decode($config['jwt'], 'test-secret', ['HS256'])), true);

        $this->assertArrayNotHasKey('user', $payload);
        $this->assertSame(['content'], $payload['scopes']);
        $this->assertSame('site', $payload['type']);
    }

    public function testTwoSitesRenderTwoFramesOfTheirOwn(): void
    {
        $first = new TestApplication();
        $first->siteBaseUrl = 'https://first.example.com';

        $second = new TestApplication();
        $second->siteBaseUrl = 'https://second.example.com';

        $figures = new PageFiguresBoxParams(self::everyFigure());

        $one = $this->messages(EmbedMarkup::of(
            (new EmbedService(EmbedMarkup::core($first)))->pageFigures($figures)
        ))['config'];
        $other = $this->messages(EmbedMarkup::of(
            (new EmbedService(new SyncCore($second, 'https://other-core.example.com/sync-core')))->pageFigures($figures)
        ))['config'];

        $this->assertSame('core.example.com', $one['syncCoreDomain']);
        $this->assertSame('other-core.example.com', $other['syncCoreDomain']);
        $this->assertSame('https://first.example.com', $one['baseUrl']);
        $this->assertSame('https://second.example.com', $other['baseUrl']);
    }

    public function testTheOnlyConstantOfThisLibraryOnTheWireIsTheId(): void
    {
        // The frame's host is the embed deployment's, which the environment
        // names: two deployments give two different urls for the same page, and
        // the path is the only part this library decides.
        putenv('CONTENT_SYNC_CLOUD_EMBED_URL=https://embed.test.example.com');
        $one = $this->frameUrl();

        putenv('CONTENT_SYNC_CLOUD_EMBED_URL=https://embed.other.example.com');
        $other = $this->frameUrl();

        $this->assertNotSame($one, $other);
        $this->assertSame('https://embed.test.example.com/box/page-figures', $one);
        $this->assertSame('https://embed.other.example.com/box/page-figures', $other);
    }

    /**
     * The url the rendered frame points at.
     */
    private function frameUrl(): string
    {
        $html = EmbedMarkup::of($this->pageFigures());

        $this->assertSame(1, preg_match('@<iframe id="[^"]*" src="([^"]*)"@', $html, $matches));

        return $matches[1];
    }

    /**
     * The entry point, called the way a module calls it.
     */
    private function pageFigures(?PageFiguresBoxParams $figures = null): IEmbedFeature
    {
        return (new EmbedService(EmbedMarkup::core()))
            ->pageFigures($figures ?? new PageFiguresBoxParams(self::everyFigure()));
    }

    /**
     * The two payloads the frame is sent once it is ready, by their name.
     *
     * @return array{config: array, options: array}
     */
    private function messages(string $html): array
    {
        $messages = [];

        foreach (['config', 'options'] as $name) {
            $this->assertSame(1, preg_match('@^\s*'.$name.': (\{.*\}),$@m', $html, $matches), 'the frame is sent a '.$name.' message');
            $messages[$name] = json_decode($matches[1], true);
        }

        return $messages;
    }

    /**
     * A page whose every figure arrived.
     */
    private static function everyFigure(): array
    {
        return [
            'entity_type' => 'article',
            'entity_uuid' => 'f1b0c0de-0000-4000-8000-000000000001',
            'langcode' => 'en',
            'content_health_percent_0_to_100' => 84,
            'open_issue_count' => 3,
            'content_priority' => 300,
            'cited_in_answers_last_30_days' => 12,
            'summary_updated' => 1758240000,
            'tags' => [
                ['key' => 'pricing', 'name' => 'Pricing'],
            ],
        ];
    }
}
