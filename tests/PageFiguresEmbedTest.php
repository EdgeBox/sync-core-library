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
        $figures = new PageFiguresBoxParams(self::everyFigure());
        $html = EmbedMarkup::of($this->pageFigures($figures));

        $this->assertSame(
            $figures->toOptions() + ['embedSize' => 'box'],
            $this->message($html, 'options')
        );
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

    public function testTheFrameCarriesTheSitesOwnTokenAndNoPersonsIdentity(): void
    {
        $html = EmbedMarkup::of($this->pageFigures());
        $config = $this->message($html, 'config');

        $this->assertSame(['syncCoreDomain', 'baseUrl', 'featureFlags', 'jwt'], array_keys($config));

        // The recording replaces the token, so the render is read again for it.
        $rendered = (string) $this->pageFigures()->run()->getRenderedHtml();
        $payload = json_decode(json_encode(JWT::decode($this->message($rendered, 'config')['jwt'], 'test-secret', ['HS256'])), true);

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

        $one = $this->message(EmbedMarkup::of(
            (new EmbedService(EmbedMarkup::core($first)))->pageFigures($figures)
        ), 'config');
        $other = $this->message(EmbedMarkup::of(
            (new EmbedService(new SyncCore($second, 'https://other-core.example.com/sync-core')))->pageFigures($figures)
        ), 'config');

        $this->assertSame('core.example.com', $one['syncCoreDomain']);
        $this->assertSame('other-core.example.com', $other['syncCoreDomain']);
        $this->assertSame('https://first.example.com', $one['baseUrl']);
        $this->assertSame('https://second.example.com', $other['baseUrl']);
    }

    public function testTheOnlyConstantOfThisLibraryOnTheWireIsTheId(): void
    {
        putenv('CONTENT_SYNC_CLOUD_EMBED_URL=https://embed.test.example.com');

        $html = EmbedMarkup::of($this->pageFigures());

        $this->assertStringContainsString('src="https://embed.test.example.com/box/page-figures"', $html);
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
     * The payload the frame is sent under the given name.
     *
     * @return array
     */
    private function message(string $html, string $name): array
    {
        $this->assertSame(1, preg_match('@^\s*'.$name.': (\{.*\}),$@m', $html, $matches), 'the frame is sent a '.$name.' message');

        return json_decode($matches[1], true);
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
            'content_health_summary' => 'The page answers the question it ranks for.',
            'open_issue_count' => 3,
            'content_priority' => PageFiguresBoxParams::PRIORITY_HIGH,
            'cited_in_answers_last_30_days' => 12,
            'summary_updated' => 1758240000,
            'tags' => [
                ['key' => 'pricing', 'name' => 'Pricing'],
            ],
        ];
    }
}
