<?php

declare(strict_types=1);

namespace EdgeBox\SyncCore\Tests\Support;

use EdgeBox\SyncCore\Interfaces\Embed\IEmbedFeature;
use EdgeBox\SyncCore\V2\Embed\Embed;
use EdgeBox\SyncCore\V2\Embed\EmbedService;
use EdgeBox\SyncCore\V2\SyncCore;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

/**
 * The rendered markup of an embed, ready to be compared against a recording.
 *
 * Two renders of one embed differ in exactly two places: the frame's generated
 * id carries a uniqid for every size but `page`, and the token carries the
 * moment it expires. Replacing those two with a fixed value is what lets a
 * recording assert everything else byte for byte.
 *
 * @internal
 */
final class EmbedMarkup
{
    /**
     * Where the markup of the released embeds is recorded.
     */
    public static function recordingPath(): string
    {
        return __DIR__.'/../fixtures/released-embeds.json';
    }

    /**
     * The markup one embed renders, with the two volatile values replaced.
     */
    public static function of(IEmbedFeature $embed): string
    {
        // The resizer script is emitted once per page, so a render that follows
        // another one omits it. Every recording is taken as the first render.
        Embed::$iframeResizerAdded = '';

        return self::normalize((string) $embed->run()->getRenderedHtml());
    }

    /**
     * The markup with the generated id and every token replaced by a fixed
     * value. A token is matched by its own shape rather than by the key it sits
     * under, so the one an embed carries in its options is replaced as well as
     * the one the configuration carries.
     */
    public static function normalize(string $html): string
    {
        $html = preg_replace('@contentSyncEmbed-[a-z0-9-]+@', 'contentSyncEmbed-ID', $html);

        return preg_replace('@eyJ[A-Za-z0-9_-]+\.[A-Za-z0-9_-]+\.[A-Za-z0-9_-]+@', 'TOKEN', $html);
    }

    /**
     * A Sync Core whose application answers every request with an empty body.
     */
    public static function core(?TestApplication $application = null): SyncCore
    {
        $application = $application ?? new TestApplication();
        $application->httpClient = new Client([
            'handler' => HandlerStack::create(new MockHandler(array_fill(0, 50, new Response(200, [], '{}')))),
        ]);

        return new SyncCore($application, 'https://core.example.com/sync-core');
    }

    /**
     * Every embed the released library offers, by the class that renders it.
     *
     * @return array<string, IEmbedFeature>
     */
    public static function releasedEmbeds(SyncCore $core): array
    {
        $service = new EmbedService($core);

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
            $service->governanceContentInventory([]),
        ];

        $by_class = [];
        foreach ($embeds as $embed) {
            $by_class[get_class($embed)] = $embed;
        }

        return $by_class;
    }

    /**
     * The markup every released embed renders, by the class that renders it.
     *
     * @return array<string, string>
     */
    public static function releasedMarkup(SyncCore $core): array
    {
        $markup = [];
        foreach (self::releasedEmbeds($core) as $class => $embed) {
            $markup[$class] = self::of($embed);
        }

        ksort($markup);

        return $markup;
    }
}
