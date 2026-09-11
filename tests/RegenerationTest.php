<?php

declare(strict_types=1);

namespace EdgeBox\SyncCore\Tests;

use EdgeBox\SyncCore\V2\Raw\Model\AuthenticationType;
use EdgeBox\SyncCore\V2\Raw\ObjectSerializer;
use PHPUnit\Framework\TestCase;

/**
 * src/V2/Raw is generator output. The regeneration job,
 * .github/workflows/regenerate-client.yml, generates it from the Sync Core
 * OpenAPI document and re-applies patches/openapi-client-fixes.patch, which
 * corrects two defects of the generator: the generator calls a JSON encoder
 * that exists only in Guzzle 7 while the package supports Guzzle 6 as well,
 * and it hands the deserializer model names without their namespace.
 * Dispatched with assert_empty, that job is what compares the committed client
 * against a fresh generation; it needs the generator and the Sync Core
 * document, so it runs in CI.
 *
 * These assertions cover what a checkout can answer on its own: the two
 * corrections are in the committed client, and they hold under the Guzzle
 * version the lock file resolves. A generation that reached the branch without
 * its patch turns them red.
 *
 * @internal
 */
final class RegenerationTest extends TestCase
{
    public function testJsonIsEncodedThroughTheVersionIndependentEntryPoint(): void
    {
        $this->assertSame('{"one":1}', ObjectSerializer::guzzleJsonEncode(['one' => 1]));
    }

    public function testNoRequestBodyCallsTheGuzzle7EncoderDirectly(): void
    {
        $api = file_get_contents(__DIR__.'/../src/V2/Raw/Api/DefaultApi.php');

        $this->assertIsString($api);
        $this->assertStringNotContainsString('\GuzzleHttp\Utils::jsonEncode(', $api);
    }

    public function testAModelNameWithoutItsNamespaceDeserializes(): void
    {
        $value = AuthenticationType::getAllowableEnumValues()[0];

        $this->assertSame($value, ObjectSerializer::deserialize($value, 'AuthenticationType'));
    }
}
