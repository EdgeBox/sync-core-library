<?php

declare(strict_types=1);

namespace EdgeBox\SyncCore\Tests;

use EdgeBox\SyncCore\Tests\Support\TestApplication;
use EdgeBox\SyncCore\V2\Governance\GovernanceService;
use EdgeBox\SyncCore\V2\Raw\Model\AuthoredBrief;
use EdgeBox\SyncCore\V2\Raw\Model\AuthoredTitle;
use EdgeBox\SyncCore\V2\Raw\Model\ContentRecommendationKind;
use EdgeBox\SyncCore\V2\Raw\Model\ContentRecommendationLinkTier;
use EdgeBox\SyncCore\V2\Raw\Model\ContentRecommendationTargetState;
use EdgeBox\SyncCore\V2\Raw\Model\TextFormat;
use EdgeBox\SyncCore\V2\SyncCore;
use PHPUnit\Framework\TestCase;

/**
 * The recommendations a site reads off an optimize-content trigger.
 *
 * tests/fixtures/optimize-content-trigger-body.json is the Sync Core's own
 * export of 24 September 2026: the exact body its trigger sends, taken
 * unchanged from the specification that pins that body. Reading it here is
 * what keeps this library on the wire a site actually receives, rather than on
 * a shape invented on this side of it.
 *
 * @internal
 */
final class OptimizeContentRecommendationsTest extends TestCase
{
    public function testTheRealExportIsReadPartForPart(): void
    {
        $request = $this->parse($this->realExport());

        $recommendations = $request->getRecommendations();
        $this->assertCount(1, $recommendations);

        $recommendation = $recommendations[0];
        $this->assertSame('66f300000000000000000b01', $recommendation->getId());
        $this->assertSame(ContentRecommendationKind::FAQ, $recommendation->getKind());
        $this->assertSame('en-US', $recommendation->getLocale()->getKey());
        $this->assertSame('English (en-US)', $recommendation->getLocale()->getName());

        $title = $recommendation->getTitle();
        $this->assertSame('What does it cost?', $title->getText());
        $this->assertSame(TextFormat::PLAIN, $title->getFormat());
        $this->assertSame(AuthoredTitle::PROVENANCE_MODEL_INFERRED, $title->getProvenance());
        $this->assertSame('2026-01-01T00:00:00.000Z', $title->getAt());

        $content = $recommendation->getContent();
        $this->assertSame('It costs little.', $content->getText());
        $this->assertSame(TextFormat::MARKDOWN, $content->getFormat());

        $brief = $recommendation->getBrief();
        $this->assertSame('Lead with the price.', $brief->getText());
        $this->assertSame(AuthoredBrief::PROVENANCE_OPERATOR, $brief->getProvenance());
    }

    public function testTheRealExportCarriesTheTargetAndTheEvidenceBehindIt(): void
    {
        $recommendation = $this->parse($this->realExport())->getRecommendations()[0];

        $target = $recommendation->getTarget();
        $this->assertSame(ContentRecommendationTargetState::MISSING, $target->getState());
        $this->assertSame(ContentRecommendationLinkTier::_400_CITED_ANSWER, $target->getLinkTier());
        $this->assertCount(1, $target->getEvidence());
        $this->assertSame('body', $target->getEvidence()[0]->getPath());
        $this->assertSame('No price stated.', $target->getEvidence()[0]->getReasoning());
        $this->assertNull($target->getEvidence()[0]->getValue());
        $this->assertFalse($target->getEvidence()[0]->wasTruncated());

        $evidence = $recommendation->getEvidence();
        $this->assertSame('pricing', $evidence->getClusterKey());
        $this->assertEqualsWithDelta(0.5, $evidence->getUnpromptedRate(), 0.0001);
        $this->assertSame(10, $evidence->getUnpromptedDenominator());
        $this->assertEqualsWithDelta(0.25, $evidence->getGapRate(), 0.0001);
        $this->assertSame(4, $evidence->getGapDenominator());

        $this->assertCount(1, $evidence->getGapSources());
        $this->assertSame('authority.example', $evidence->getGapSources()[0]->getKey());
        $this->assertSame('example.com', $evidence->getGapSources()[0]->getName());

        $this->assertCount(1, $evidence->getRuns());
        $run = $evidence->getRuns()[0];
        $this->assertSame('66f600000000000000000001', $run->getId());
        $this->assertSame('engine.openai.chatgpt', $run->getEngine()->getKey());
        $this->assertSame('ChatGPT', $run->getEngine()->getName());
        $this->assertSame('How much does it cost?', $run->getPromptText());
        $this->assertSame('2026-01-01T00:00:00.000Z', $run->getAnsweredAt());

        $this->assertCount(1, $evidence->getCoverage());
        $coverage = $evidence->getCoverage()[0];
        $this->assertSame('ChatGPT', $coverage->getEngine()->getName());
        $this->assertSame('Discovery', $coverage->getPurpose()->getName());
        $this->assertSame(3, $coverage->getAnswers());
    }

    public function testTheEvidenceListsKeepTheOrderTheyArriveIn(): void
    {
        $body = $this->realExport();
        $evidence = &$body['recommendations'][0]['evidence'];
        $evidence['gapSources'][] = ['key' => 'authority.second', 'name' => 'second.example'];
        $evidence['runs'][] = ['id' => 'the second run', 'engine' => ['key' => 'engine.second', 'name' => 'Second'], 'answeredAt' => '2026-01-02T00:00:00.000Z'];
        $evidence['coverage'][] = ['engine' => ['key' => 'engine.second', 'name' => 'Second'], 'purpose' => ['key' => 'purpose.comparison', 'name' => 'Comparison'], 'answers' => 9];
        unset($evidence);

        $read = $this->parse($body)->getRecommendations()[0]->getEvidence();

        $this->assertSame(['authority.example', 'authority.second'], array_map(fn ($source) => $source->getKey(), $read->getGapSources()));
        $this->assertSame(['66f600000000000000000001', 'the second run'], array_map(fn ($run) => $run->getId(), $read->getRuns()));
        $this->assertSame([3, 9], array_map(fn ($entry) => $entry->getAnswers(), $read->getCoverage()));
        $this->assertSame(['ChatGPT', 'Second'], array_map(fn ($entry) => $entry->getEngine()->getName(), $read->getCoverage()));
    }

    public function testAListThatIsNotOneReadsAsAnEmptyList(): void
    {
        $body = $this->realExport();
        $body['recommendations'][0]['target']['evidence'] = 'not a list';
        $body['recommendations'][0]['evidence']['runs'] = 'not a list';
        $body['recommendations'][0]['evidence']['gapSources'] = 7;
        $body['recommendations'][0]['evidence']['coverage'] = (object) ['first' => ['answers' => 3]];

        $recommendation = $this->parse($body)->getRecommendations()[0];

        $this->assertSame([], $recommendation->getTarget()->getEvidence());
        $this->assertSame([], $recommendation->getEvidence()->getRuns());
        $this->assertSame([], $recommendation->getEvidence()->getGapSources());
        $this->assertSame([], $recommendation->getEvidence()->getCoverage());
    }

    public function testTheRestOfTheRealExportStillReadsAsItDid(): void
    {
        $request = $this->parse($this->realExport());

        $this->assertSame('66f300000000000000000a01', $request->getOptimizationId());
        $this->assertSame('page.pricing', $request->getContentItemKey());
        $this->assertSame('en-US', $request->getTargetLocale());
        $this->assertSame(['faq-section'], $request->getOptimizationTypeKeys());
        $this->assertSame('Pricing', $request->getPage()['title']);
        $this->assertCount(1, $request->getIssues());
        $this->assertSame('missing-faq', $request->getIssues()[0]->getTypeKey());
        $this->assertFalse($request->wasTruncated());
    }

    public function testTheRealExportNamesThePageASiteCanFind(): void
    {
        $request = $this->parse($this->realExport());

        $this->assertSame('https://example.com/pricing', $request->getPageUrl());
        $this->assertSame('https://example.com/pricing', $request->getPage()['url']);

        $entity = $request->getPageEntity();
        $this->assertSame('node', $entity->getEntityType());
        $this->assertSame('6f1c2b8e-9a34-4f5d-8f2a-9b7c1d2e3f40', $entity->getRemoteUuid());
        $this->assertSame('en', $entity->getLangcode());
    }

    public function testAPageTheSenderNamesNoEntityForIsReadAsNone(): void
    {
        $body = $this->realExport();
        unset($body['pageEntity']);

        $this->assertNull($this->parse($body)->getPageEntity());
    }

    public function testAPageEntityThatIsNotOneIsReadAsNone(): void
    {
        $body = $this->realExport();
        $body['pageEntity'] = 'not a reference';

        $this->assertNull($this->parse($body)->getPageEntity());
    }

    public function testAPageEntityWithNothingInItReadsEmptyRatherThanRaising(): void
    {
        $body = $this->realExport();
        $body['pageEntity'] = [];

        $entity = $this->parse($body)->getPageEntity();

        $this->assertSame('', $entity->getEntityType());
        $this->assertSame('', $entity->getRemoteUuid());
        $this->assertSame('', $entity->getLangcode());
    }

    public function testAPageTheSenderHoldsNoUrlForIsReadAsNone(): void
    {
        $body = $this->realExport();
        unset($body['page']['url']);

        $this->assertNull($this->parse($body)->getPageUrl());
    }

    public function testNoRecommendationsIsReadAsAnEmptyList(): void
    {
        $body = $this->realExport();
        $body['recommendations'] = null;

        $this->assertSame([], $this->parse($body)->getRecommendations());
    }

    public function testAKindThisLibraryHasNoNameForIsHandedOnWithItsParts(): void
    {
        $body = $this->realExport();
        $body['recommendations'] = [[
            'id' => 'r-1',
            'kind' => 'a-kind-from-a-later-release',
            'locale' => ['key' => 'en-US', 'name' => 'English (en-US)'],
            'title' => ['text' => 'Say what it costs', 'format' => TextFormat::PLAIN, 'provenance' => 'operator', 'at' => '2026-01-01T00:00:00.000Z'],
        ]];

        $recommendation = $this->parse($body)->getRecommendations()[0];

        $this->assertSame('a-kind-from-a-later-release', $recommendation->getKind());
        $this->assertSame('Say what it costs', $recommendation->getTitle()->getText());
        $this->assertNull($recommendation->getContent());
        $this->assertNull($recommendation->getBrief());
        $this->assertNull($recommendation->getTarget());
        $this->assertNull($recommendation->getEvidence());
    }

    public function testATargetEvidenceItemCarriesWhatWasReadAndWhetherItWasCutShort(): void
    {
        $body = $this->realExport();
        $body['recommendations'][0]['target']['evidence'] = [
            ['path' => 'title', 'value' => 'Pricing', 'isTruncated' => false, 'reasoning' => 'No figure in it.'],
            ['path' => 'body', 'value' => 'The first sentence of a long page', 'isTruncated' => true],
        ];

        $evidence = $this->parse($body)->getRecommendations()[0]->getTarget()->getEvidence();

        $this->assertSame('title', $evidence[0]->getPath());
        $this->assertSame('Pricing', $evidence[0]->getValue());
        $this->assertFalse($evidence[0]->wasTruncated());
        $this->assertSame('No figure in it.', $evidence[0]->getReasoning());

        $this->assertSame('The first sentence of a long page', $evidence[1]->getValue());
        $this->assertTrue($evidence[1]->wasTruncated());
        $this->assertNull($evidence[1]->getReasoning());
    }

    public function testTheListKeepsTheOrderTheOptimizationNamesItIn(): void
    {
        $body = $this->realExport();
        $first = $body['recommendations'][0];
        $second = array_merge($first, ['id' => 'the second one', 'kind' => ContentRecommendationKind::PAGE_RETIRE]);
        $body['recommendations'] = [$first, $second];

        $recommendations = $this->parse($body)->getRecommendations();

        $this->assertSame(['66f300000000000000000b01', 'the second one'], [$recommendations[0]->getId(), $recommendations[1]->getId()]);
        $this->assertSame(
            [ContentRecommendationKind::FAQ, ContentRecommendationKind::PAGE_RETIRE],
            [$recommendations[0]->getKind(), $recommendations[1]->getKind()]
        );
    }

    public function testAnEntryThatIsNotOneIsPassedOverRatherThanRaised(): void
    {
        $body = $this->realExport();
        $body['recommendations'] = ['not an entry', $body['recommendations'][0], 7];

        $recommendations = $this->parse($body)->getRecommendations();

        $this->assertCount(1, $recommendations);
        $this->assertSame(ContentRecommendationKind::FAQ, $recommendations[0]->getKind());
    }

    public function testAPartThatDidNotArriveReadsEmptyRatherThanRaising(): void
    {
        $body = $this->realExport();
        $body['recommendations'] = [['id' => 'r-1', 'kind' => ContentRecommendationKind::FAQ, 'locale' => 'not a reference']];

        $recommendation = $this->parse($body)->getRecommendations()[0];

        $this->assertSame('', $recommendation->getLocale()->getKey());
        $this->assertSame('', $recommendation->getTitle()->getText());
        $this->assertSame('', $recommendation->getTitle()->getFormat());
        $this->assertNull($recommendation->getContent());
        $this->assertNull($recommendation->getBrief());
        $this->assertNull($recommendation->getTarget());
        $this->assertNull($recommendation->getEvidence());
    }

    /**
     * @return array the body of the trigger, as the Sync Core sends it
     */
    private function realExport(): array
    {
        return json_decode(file_get_contents(__DIR__.'/fixtures/optimize-content-trigger-body.json'), true);
    }

    private function parse(array $body): \EdgeBox\SyncCore\Interfaces\Governance\IOptimizeContentRequest
    {
        $governance = new GovernanceService(new SyncCore(new TestApplication(), 'https://core.example.com/sync-core'));

        return $governance->parseOptimizeContentRequest(['optimizationId' => $body['optimizationId']], $body);
    }
}
