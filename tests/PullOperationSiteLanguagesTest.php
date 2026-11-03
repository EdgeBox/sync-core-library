<?php

declare(strict_types=1);

namespace EdgeBox\SyncCore\Tests;

use EdgeBox\SyncCore\Interfaces\ISyncCore;
use EdgeBox\SyncCore\V2\Syndication\PullOperation;

/**
 * The pull response body reports the languages the site stored the pulled
 * content in.
 *
 * @internal
 */
final class PullOperationSiteLanguagesTest extends SyncCoreTestCase
{
    private const DEEP_LINK = 'https://site.example.com/node/1';

    private const TARGET_UUID = '11111111-2222-4333-8444-555555555555';

    public function testTheMappedRootLanguageReplacesTheSourceLanguageAndTheTranslationFoldedIntoItIsLeftOut(): void
    {
        $operation = $this->pullOperation(['it', 'de']);
        $operation->setSiteLanguages(['en' => 'it']);

        $body = $operation->getResponseBody(self::DEEP_LINK, self::TARGET_UUID);

        $this->assertSame('it', $body['language']);
        $this->assertSame(['de'], array_column($body['translations'], 'language'));
        $this->assertSame([0], array_keys($body['translations']));
        $this->assertSame(self::DEEP_LINK, $body['viewUrl']);
        $this->assertSame(self::TARGET_UUID, $body['translations'][0]['remoteUuid']);
    }

    public function testNoTwoEntriesShareALanguageAfterTheRewrite(): void
    {
        $operation = $this->pullOperation(['it', 'de', 'fr']);
        $operation->setSiteLanguages(['en' => 'it', 'fr' => 'de']);

        $body = $operation->getResponseBody(self::DEEP_LINK, self::TARGET_UUID);

        $languages = array_merge([$body['language']], array_column($body['translations'], 'language'));
        $this->assertSame(['it', 'de'], $languages);
        $this->assertSame('fr', $body['translations'][0]['name']);
    }

    public function testTheTranslationTheMapMovesWinsWhateverItsPositionInTheBody(): void
    {
        foreach ([['it', 'de'], ['de', 'it']] as $translation_languages) {
            $operation = $this->pullOperation($translation_languages);
            $operation->setSiteLanguages(['de' => 'it']);

            $body = $operation->getResponseBody(self::DEEP_LINK);

            $this->assertSame('en', $body['language']);
            $this->assertSame(['it'], array_column($body['translations'], 'language'));
            $this->assertSame('de', $body['translations'][0]['name']);
        }
    }

    public function testATranslationTheMapMovesToTheRootLanguageIsLeftOut(): void
    {
        $operation = $this->pullOperation(['fr', 'de']);
        $operation->setSiteLanguages(['fr' => 'en']);

        $body = $operation->getResponseBody(self::DEEP_LINK);

        $this->assertSame('en', $body['language']);
        $this->assertSame(['de'], array_column($body['translations'], 'language'));
    }

    public function testEntriesTheMapDoesNotMoveAreNeverLeftOut(): void
    {
        $expected = json_encode($this->pullOperation(['en', 'it', 'it'])->getResponseBody(self::DEEP_LINK));

        $operation = $this->pullOperation(['en', 'it', 'it']);
        $operation->setSiteLanguages(['fr' => 'it']);
        $body = $operation->getResponseBody(self::DEEP_LINK);

        $this->assertSame($expected, json_encode($body));
        $this->assertSame(['en', 'it', 'it'], array_column($body['translations'], 'language'));
    }

    public function testAnEmptySiteLanguageIsIgnored(): void
    {
        $expected = json_encode($this->pullOperation(['it'])->getResponseBody(self::DEEP_LINK));

        $operation = $this->pullOperation(['it']);
        $operation->setSiteLanguages(['en' => '', 'it' => '']);

        $this->assertSame($expected, json_encode($operation->getResponseBody(self::DEEP_LINK)));
    }

    public function testATranslationLanguageIsReplacedThroughTheMap(): void
    {
        $operation = $this->pullOperation(['de']);
        $operation->setSiteLanguages(['de' => 'de-ch']);

        $body = $operation->getResponseBody(self::DEEP_LINK);

        $this->assertSame('en', $body['language']);
        $this->assertSame(['de-ch'], array_column($body['translations'], 'language'));
    }

    public function testWithoutAMapOrWithAnEmptyMapTheBodyIsUnchanged(): void
    {
        $expected = json_encode($this->pullOperation(['it', 'de'])->getResponseBody(self::DEEP_LINK, self::TARGET_UUID));

        $with_empty_map = $this->pullOperation(['it', 'de']);
        $with_empty_map->setSiteLanguages([]);

        $this->assertSame($expected, json_encode($with_empty_map->getResponseBody(self::DEEP_LINK, self::TARGET_UUID)));
        $this->assertSame(['it', 'de'], array_column(json_decode($expected, true)['translations'], 'language'));
    }

    public function testAMapEntryForALanguageTheBodyDoesNotCarryChangesNothing(): void
    {
        $expected = json_encode($this->pullOperation(['it', 'de'])->getResponseBody(self::DEEP_LINK, self::TARGET_UUID));

        $operation = $this->pullOperation(['it', 'de']);
        $operation->setSiteLanguages(['fr' => 'it']);

        $this->assertSame($expected, json_encode($operation->getResponseBody(self::DEEP_LINK, self::TARGET_UUID)));
    }

    public function testTheCapabilityConstantNamesTheSyncCoreFlag(): void
    {
        $this->assertSame('syndication:language-mapping:available', ISyncCore::FEATURE_LANGUAGE_MAPPING_AVAILABLE);
    }

    /**
     * A pull of an English root with one translation per given language; each
     * translation is named after its language.
     *
     * @param string[] $translation_languages
     */
    private function pullOperation(array $translation_languages): PullOperation
    {
        $translations = [];
        foreach ($translation_languages as $language) {
            $translations[] = $this->revision($language);
        }

        $body = $this->revision('en');
        $body['translations'] = $translations;

        return new PullOperation($this->syncCoreWithResponses([]), $body, false);
    }

    /**
     * @return array<string, mixed>
     */
    private function revision(string $language): array
    {
        return [
            'name' => $language,
            'published' => true,
            'remoteUuid' => 'aaaaaaaa-0000-4000-8000-000000000001',
            'language' => $language,
            'poolMachineNames' => ['content'],
            'isTranslationRoot' => 'en' === $language,
            'flowMachineName' => 'content',
        ];
    }
}
