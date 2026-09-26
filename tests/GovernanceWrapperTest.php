<?php

declare(strict_types=1);

namespace EdgeBox\SyncCore\Tests;

use EdgeBox\SyncCore\Exception\BadRequestException;
use EdgeBox\SyncCore\Exception\ConflictException;
use EdgeBox\SyncCore\Exception\ForbiddenException;
use EdgeBox\SyncCore\Exception\NotFoundException;
use EdgeBox\SyncCore\Exception\SyncCoreException;
use EdgeBox\SyncCore\Exception\UnauthorizedException;
use EdgeBox\SyncCore\Interfaces\Governance\ActingUser;
use EdgeBox\SyncCore\Interfaces\Governance\ExternalDraftOutcome;
use EdgeBox\SyncCore\Interfaces\Governance\IGovernanceService;
use EdgeBox\SyncCore\Interfaces\ISyncCore;
use EdgeBox\SyncCore\Tests\Support\TestApplication;
use EdgeBox\SyncCore\V2\Governance\GovernanceService;
use EdgeBox\SyncCore\V2\Raw\Model\ContentOptimizationExternalOutcome;
use EdgeBox\SyncCore\V2\Raw\Model\ContentRecommendationKind;
use EdgeBox\SyncCore\V2\SyncCore;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * The wrappers are instantiated directly rather than through getGovernanceService(),
 * whose method-level static cache would otherwise bind one GovernanceService to the
 * first test's SyncCore for the rest of the process.
 *
 * @internal
 */
final class GovernanceWrapperTest extends SyncCoreTestCase
{
    public function testGetGovernanceServiceExposesTheService(): void
    {
        $core = new SyncCore(new TestApplication(), 'https://core.example.com/sync-core');

        $this->assertInstanceOf(IGovernanceService::class, $core->getGovernanceService());
    }

    public function testTheInboundTriggerParsesAndAnswers(): void
    {
        $governance = new GovernanceService(new SyncCore(new TestApplication(), 'https://core.example.com/sync-core'));

        $request = $governance->parseOptimizeContentRequest(
            ['optimizationId' => 'opt-1'],
            [
                'optimizationId' => 'opt-1',
                'contentItemKey' => 'https://site.example.com/page',
                'targetLocale' => 'de',
                'page' => ['title' => 'Home', 'contentHealthPercent0To100' => 80, 'contentPriority' => '300-high', 'citedInAnswersLast30Days' => 5],
                'optimizationTypeKeys' => ['seo', 'readability'],
                'issues' => [
                    ['key' => 'i1', 'typeKey' => 'missing-meta', 'typeName' => 'Missing meta', 'priority' => '400-critical', 'instruction' => 'Add a meta description', 'evidence' => 'none found'],
                ],
                'recommendations' => [[
                    'id' => 'r1',
                    'kind' => ContentRecommendationKind::FAQ,
                    'locale' => ['key' => 'de', 'name' => 'German (de)'],
                    'title' => ['text' => 'What does it cost?', 'format' => 'text/plain', 'provenance' => 'model-inferred', 'at' => '2026-01-01T00:00:00.000Z'],
                ]],
                'truncated' => true,
            ]
        );

        $this->assertSame('opt-1', $request->getOptimizationId());
        $this->assertSame('https://site.example.com/page', $request->getContentItemKey());
        $this->assertSame('de', $request->getTargetLocale());
        $this->assertSame(['seo', 'readability'], $request->getOptimizationTypeKeys());
        $this->assertSame('Home', $request->getPage()['title']);
        $this->assertTrue($request->wasTruncated());

        $issues = $request->getIssues();
        $this->assertCount(1, $issues);
        $this->assertSame('missing-meta', $issues[0]->getTypeKey());
        $this->assertSame('Add a meta description', $issues[0]->getInstruction());

        $recommendation = $request->getRecommendations()[0];
        $this->assertSame('r1', $recommendation->getId());
        $this->assertSame(ContentRecommendationKind::FAQ, $recommendation->getKind());
        $this->assertSame('de', $recommendation->getLocale()->getKey());
        $this->assertSame('What does it cost?', $recommendation->getTitle()->getText());

        $this->assertSame(['accepted' => true], $request->accept());
        $this->assertSame(['accepted' => false, 'reason' => 'busy'], $request->refuse('busy'));
    }

    public function testTheExternalDraftPostRoundTrips(): void
    {
        $core = $this->syncCoreWithResponses([new Response(201, [], json_encode(['id' => 'opt-1', 'status' => '300-user-review']))]);

        $result = (new GovernanceService($core))
            ->postExternalDraft('https://site.example.com/page', 'rev-1', '<p>hello</p>')
            ->forOptimization('opt-1')
            ->withOptimizationTypeKeys(['seo'])
            ->fixingIssues(['i1'])
            ->execute()
        ;

        $this->assertSame('opt-1', $result->getOptimizationId());
        $this->assertSame('300-user-review', $result->getStatus());
        $this->assertFalse($result->wasExisting());

        $sent = $this->sentRequest();
        $this->assertSame('POST', $sent->getMethod());
        $this->assertStringContainsString('/content-optimization/external', (string) $sent->getUri());

        $body = $this->sentBody();
        $this->assertSame('https://site.example.com/page', $body['contentItemKey']);
        $this->assertSame('rev-1', $body['externalRevisionId']);
        $this->assertSame('<p>hello</p>', $body['renderedHtml']);
        $this->assertSame('opt-1', $body['optimizationId']);
        $this->assertSame(['seo'], $body['optimizationTypeKeys']);
        $this->assertSame(['i1'], $body['sourceIssueKeys']);
    }

    public function testAnIdempotentRepeatIsReportedAsExisting(): void
    {
        $core = $this->syncCoreWithResponses([new Response(200, [], json_encode(['id' => 'opt-1', 'status' => '300-user-review']))]);

        $result = (new GovernanceService($core))
            ->postExternalDraft('https://site.example.com/page', 'rev-1', '<p>hello</p>')
            ->forOptimization('opt-1')
            ->execute()
        ;

        $this->assertTrue($result->wasExisting());
    }

    public function testTheExternalDraftOutcomeCarriesItsNamedValues(): void
    {
        $outcome = new ExternalDraftOutcome(
            optimizationId: 'opt-1',
            externalRevisionId: 'rev-1',
            outcome: ContentOptimizationExternalOutcome::PUBLISHED,
        );

        $this->assertSame('opt-1', $outcome->getOptimizationId());
        $this->assertSame('rev-1', $outcome->getExternalRevisionId());
        $this->assertSame('published', $outcome->getOutcome());
    }

    public function testTheExternalDraftOutcomeReportRoundTrips(): void
    {
        $core = $this->syncCoreWithResponses([new Response(201, [], json_encode(['id' => 'opt-1', 'status' => '500-applied']))]);

        $result = (new GovernanceService($core))
            ->reportExternalDraftOutcome(new ExternalDraftOutcome(
                optimizationId: 'opt-1',
                externalRevisionId: 'rev-1',
                outcome: ContentOptimizationExternalOutcome::PUBLISHED,
            ))
            ->execute()
        ;

        $this->assertSame('opt-1', $result->getOptimizationId());
        $this->assertSame('500-applied', $result->getStatus());
        $this->assertFalse($result->wasExisting());

        $sent = $this->sentRequest();
        $this->assertSame('POST', $sent->getMethod());
        $this->assertStringEndsWith('/content-optimization/opt-1/external-outcome', $sent->getUri()->getPath());
        $this->assertSame(['externalRevisionId' => 'rev-1', 'outcome' => 'published'], $this->sentBody());

        $payload = $this->decodeBearer($sent);
        $this->assertSame('site', $payload['type']);
        $this->assertSame(['content'], $payload['scopes']);
    }

    public function testTheExternalDraftOutcomeReportSendsAsTheActingPerson(): void
    {
        $core = $this->syncCoreWithResponses([new Response(201, [], json_encode(['id' => 'opt-1', 'status' => '600-aborted']))]);

        (new GovernanceService($core))
            ->reportExternalDraftOutcome(
                new ExternalDraftOutcome(
                    optimizationId: 'opt-1',
                    externalRevisionId: 'rev-1',
                    outcome: ContentOptimizationExternalOutcome::DISCARDED,
                ),
                new ActingUser(['issue:own:write'], 'Ada Editor', 'ada@example.com')
            )
            ->execute()
        ;

        $this->assertSame('discarded', $this->sentBody()['outcome']);

        $payload = $this->decodeBearer($this->sentRequest());
        $this->assertSame(['content', 'issue:own:write'], $payload['scopes']);
        $this->assertSame(['name' => 'Ada Editor', 'email' => 'ada@example.com'], $payload['user']);
    }

    public function testARepeatedOutcomeReportIsReportedAsExisting(): void
    {
        $core = $this->syncCoreWithResponses([new Response(200, [], json_encode(['id' => 'opt-1', 'status' => '500-applied']))]);

        $result = (new GovernanceService($core))
            ->reportExternalDraftOutcome(new ExternalDraftOutcome(
                optimizationId: 'opt-1',
                externalRevisionId: 'rev-1',
                outcome: ContentOptimizationExternalOutcome::PUBLISHED,
            ))
            ->execute()
        ;

        $this->assertTrue($result->wasExisting());
        $this->assertSame('500-applied', $result->getStatus());
    }

    /**
     * @return array<string, array{int, class-string}>
     */
    public static function refusedOutcomeReports(): array
    {
        return [
            'an invalid body' => [400, BadRequestException::class],
            'an invalid token' => [401, UnauthorizedException::class],
            'another site\'s optimization' => [403, ForbiddenException::class],
            'an unknown optimization' => [404, NotFoundException::class],
            'another revision than the draft' => [409, ConflictException::class],
            'a server error' => [500, SyncCoreException::class],
        ];
    }

    /**
     * @param class-string $exception
     */
    #[DataProvider('refusedOutcomeReports')]
    public function testARefusedOutcomeReportRaisesTheExceptionOfItsStatus(int $status, string $exception): void
    {
        $core = $this->syncCoreWithResponses([new Response($status, [], '{"message":"refused"}')]);

        try {
            (new GovernanceService($core))
                ->reportExternalDraftOutcome(new ExternalDraftOutcome(
                    optimizationId: 'opt-1',
                    externalRevisionId: 'rev-1',
                    outcome: ContentOptimizationExternalOutcome::SUPERSEDED,
                ))
                ->execute()
            ;
            $this->fail("expected {$exception} for a {$status}");
        } catch (SyncCoreException $e) {
            $this->assertSame($exception, get_class($e));
            $this->assertSame($status, $e->getStatusCode());
        }
    }

    public function testTheOutcomeReportIsAdvertisedByItsFeatureFlag(): void
    {
        $this->assertSame('governance:aim-external-outcome:available', ISyncCore::FEATURE_AIM_EXTERNAL_OUTCOME_AVAILABLE);
    }

    public function testTheContentItemReadParsesItsSummary(): void
    {
        $json = json_encode([
            'key' => 'https://site.example.com/page',
            'name' => 'Home',
            'healthScore' => 0.8,
            'contentPriority' => '300-high',
            'issues' => ['openCount' => 3, 'types' => [['key' => 'seo'], ['key' => 'meta']]],
            'lastCrawledAt' => 1700000000000,
        ]);
        $core = $this->syncCoreWithResponses([new Response(200, [], $json)]);

        $summary = (new GovernanceService($core))->getContentItemByKey('https://site.example.com/page', '300-published');

        $this->assertSame('https://site.example.com/page', $summary->getKey());
        $this->assertSame('Home', $summary->getTitle());
        $this->assertEqualsWithDelta(0.8, $summary->getHealthScore(), 0.0001);
        $this->assertSame('300-high', $summary->getContentPriority());
        $this->assertSame(3, $summary->getOpenIssueCount());
        $this->assertSame(['seo', 'meta'], $summary->getIssueTypeKeys());
        $this->assertEquals(1700000000000, $summary->getLastCrawledAt());

        $this->assertStringContainsString('/content-item/by-key/', (string) $this->sentRequest()->getUri());
    }

    public function testTheTaxonomyTermReadParsesItsSummary(): void
    {
        $json = json_encode(['key' => 'seo', 'name' => 'SEO', 'priority' => '400-critical', 'instruction' => 'Improve SEO']);
        $core = $this->syncCoreWithResponses([new Response(200, [], $json)]);

        $summary = (new GovernanceService($core))->getTaxonomyTermByKey('seo', '300-published');

        $this->assertSame('seo', $summary->getKey());
        $this->assertSame('SEO', $summary->getName());
        $this->assertSame('400-critical', $summary->getPriority());
        $this->assertSame('Improve SEO', $summary->getInstruction());

        $this->assertStringContainsString('/taxonomy-term/by-key/', (string) $this->sentRequest()->getUri());
    }
}
