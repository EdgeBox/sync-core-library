<?php

declare(strict_types=1);

namespace EdgeBox\SyncCore\Tests;

use EdgeBox\SyncCore\Exception\BadRequestException;
use EdgeBox\SyncCore\Exception\SyncCoreException;
use EdgeBox\SyncCore\V2\SyncCore;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

/**
 * A refused request's exception message names the refused field and the
 * reason when the Sync Core's refusal gives them.
 *
 * @internal
 */
final class ValidationRefusalTest extends SyncCoreTestCase
{
    private const URL = 'https://core.example.com/sync-core/remote-entity-revision';

    private const PREFIX_400 = 'The Sync Core responded with 400 Bad Request for POST '.self::URL.' ';

    /**
     * The validation tree a refusal carries as its message.
     */
    private const TREE = [
        [
            'property' => 'embed',
            'children' => [
                [
                    'property' => '3',
                    'children' => [
                        [
                            'property' => 'viewUrl',
                            'constraints' => ['isUrl' => 'Must be a valid URL.'],
                        ],
                    ],
                ],
            ],
        ],
    ];

    public function testARefusedFieldIsNamedBeforeTheReason(): void
    {
        $exception = $this->refusal(400, ['summary' => 'Must be a valid URL.', 'property' => 'embed.3.viewUrl']);

        $this->assertInstanceOf(BadRequestException::class, $exception);
        $this->assertSame(self::PREFIX_400.'embed.3.viewUrl: Must be a valid URL.', $exception->getMessage());
    }

    public function testAReasonInSeveralSentencesFollowsTheFieldUnchanged(): void
    {
        $summary = 'Embedded content "team photo.jpg": Must be a valid URL. Rejected value: "https://a.test/team photo.jpg"';

        $exception = $this->refusal(400, ['summary' => $summary, 'property' => 'embed.3.viewUrl']);

        $this->assertInstanceOf(BadRequestException::class, $exception);
        $this->assertSame(self::PREFIX_400.'embed.3.viewUrl: '.$summary, $exception->getMessage());
    }

    public function testAReasonOfSeveralClausesIsAppendedUnchanged(): void
    {
        foreach ([
            'Must be a valid URL.; Must be shorter than or equal to 2048 characters.; Must not be empty.',
            'Must be a valid URL. Must be shorter than or equal to 2048 characters. Must not be empty.',
        ] as $summary) {
            $exception = $this->refusal(400, ['summary' => $summary, 'property' => 'embed.3.viewUrl']);

            $this->assertSame(self::PREFIX_400.'embed.3.viewUrl: '.$summary, $exception->getMessage());
        }
    }

    public function testAReasonWithoutAFieldStandsAlone(): void
    {
        $exception = $this->refusal(400, ['summary' => 'Must be a valid URL.']);

        $this->assertSame(self::PREFIX_400.'Must be a valid URL.', $exception->getMessage());
    }

    public function testABlankFieldIsNotNamed(): void
    {
        foreach (['', ' '] as $property) {
            $exception = $this->refusal(400, ['summary' => 'Must be a valid URL.', 'property' => $property]);

            $this->assertSame(self::PREFIX_400.'Must be a valid URL.', $exception->getMessage());
        }
    }

    public function testARefusalWithoutAReasonKeepsTheValidationTree(): void
    {
        $exception = $this->refusal(400, []);

        $this->assertInstanceOf(BadRequestException::class, $exception);
        $this->assertStringEndsWith(self::URL.' '.json_encode(self::TREE), $exception->getMessage());
    }

    public function testABlankOrNonTextReasonKeepsTheValidationTree(): void
    {
        foreach ([['summary' => ''], ['summary' => '  '], ['summary' => ['Must be a valid URL.']], ['summary' => null, 'property' => 'embed.3.viewUrl']] as $keys) {
            $exception = $this->refusal(400, $keys);

            $this->assertStringEndsWith(self::URL.' '.json_encode(self::TREE), $exception->getMessage());
        }
    }

    public function testATextMessageWithoutAReasonStaysAsItIs(): void
    {
        $core = $this->syncCoreWithResponses([new Response(400, [], '{"message":"Unknown flow.","statusCode":400}')]);

        $exception = $this->caught($core);

        $this->assertStringEndsWith(self::URL.' Unknown flow.', $exception->getMessage());
    }

    public function testABodyThatIsNotJsonStaysAsItIs(): void
    {
        $core = $this->syncCoreWithResponses([new Response(400, [], 'Bad Request')]);

        $exception = $this->caught($core);

        $this->assertStringEndsWith(self::URL.' Bad Request', $exception->getMessage());
    }

    public function testAnotherStatusWithAReasonNamesTheFieldToo(): void
    {
        $exception = $this->refusal(500, ['summary' => 'Must be a valid URL.', 'property' => 'embed.3.viewUrl']);

        $this->assertSame(SyncCoreException::class, get_class($exception));
        $this->assertSame(500, $exception->getStatusCode());
        $this->assertSame('The Sync Core responded with a non-OK status code for POST '.self::URL.' embed.3.viewUrl: Must be a valid URL.', $exception->getMessage());
    }

    public function testTheRawBodyStaysOnTheException(): void
    {
        $body = $this->body(['summary' => 'Must be a valid URL.', 'property' => 'embed.3.viewUrl']);
        $core = $this->syncCoreWithResponses([new Response(400, [], $body)]);

        $exception = $this->caught($core);

        $this->assertSame($body, (string) $exception->getResponseBody());
    }

    /**
     * The exception a refusal with the given status and keys beside the
     * validation tree throws.
     *
     * @param array<string, mixed> $keys
     */
    private function refusal(int $status, array $keys): SyncCoreException
    {
        return $this->caught($this->syncCoreWithResponses([new Response($status, [], $this->body($keys))]));
    }

    /**
     * @param array<string, mixed> $keys
     */
    private function body(array $keys): string
    {
        return json_encode(['message' => self::TREE, 'error' => 'Bad Request', 'statusCode' => 400] + $keys);
    }

    private function caught(SyncCore $core): SyncCoreException
    {
        try {
            $core->sendRaw(new Request('POST', self::URL), [], 1);
        } catch (SyncCoreException $e) {
            return $e;
        }

        $this->fail('expected a SyncCoreException');
    }
}
