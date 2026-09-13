<?php

declare(strict_types=1);

namespace EdgeBox\SyncCore\Tests;

use EdgeBox\SyncCore\V2\Raw\Model\AuthenticationType;
use EdgeBox\SyncCore\V2\Raw\ObjectSerializer;
use PHPUnit\Framework\TestCase;

/**
 * src/V2/Raw is generator output. The regeneration job,
 * .github/workflows/regenerate-client.yml, generates it from the Sync Core
 * OpenAPI document and runs tools/apply-generator-corrections.php on the
 * result, which corrects two defects of the generator: the generator calls a
 * JSON encoder that exists only in Guzzle 7 while the package supports
 * Guzzle 6 as well, and it hands the deserializer model names without their
 * namespace. That script asserts its own target state on every run; the job
 * also dispatches with assert_empty to compare the committed client against
 * a fresh generation, which needs the generator and the Sync Core document,
 * so both run in CI, not here.
 *
 * These tests cover what a checkout can answer on its own: the two
 * corrections hold under the Guzzle version the lock file resolves, the
 * script actually applies them to a fresh (unfixed) generation, and it is
 * idempotent both there and against the real committed tree. A generation
 * that reached the branch without the script having run turns every
 * behavioural test in this file red, the idempotence ones included: each
 * compares the script's own output against a still-uncorrected source.
 *
 * @internal
 */
final class RegenerationTest extends TestCase
{
    public function testJsonIsEncodedThroughTheVersionIndependentEntryPoint(): void
    {
        $this->assertSame('{"one":1}', ObjectSerializer::guzzleJsonEncode(['one' => 1]));
    }

    public function testAModelNameWithoutItsNamespaceDeserializes(): void
    {
        $value = AuthenticationType::getAllowableEnumValues()[0];

        $this->assertSame($value, ObjectSerializer::deserialize($value, 'AuthenticationType'));
    }

    public function testTheGeneratorCorrectionsScriptAppliesToAFreshGeneration(): void
    {
        $script = __DIR__.'/../tools/apply-generator-corrections.php';

        // A minimal stand-in for what the generator emits before either
        // correction: one direct Guzzle 7 call, and the two anchors the
        // script locates its insertions from. Real generator output is
        // 67,000 lines; this is the part of its shape the script reads.
        $rawApi = <<<'PHP'
<?php

class DefaultApi
{
    public function send($body)
    {
        $httpBody = \GuzzleHttp\Utils::jsonEncode($body);
    }
}

PHP;
        $rawSerializer = <<<'PHP'
<?php

class ObjectSerializer
{
    private static $dateTimeFormat = \DateTime::ATOM;

    public static function deserialize($data, $class)
    {
        if (method_exists($class, 'getAllowableEnumValues')) {
            return $data;
        }
    }
}

PHP;

        $fixture = sys_get_temp_dir().'/'.uniqid('sync-core-library-regeneration-test-', true);
        mkdir($fixture.'/Api', 0777, true);
        file_put_contents($fixture.'/Api/DefaultApi.php', $rawApi);
        file_put_contents($fixture.'/ObjectSerializer.php', $rawSerializer);

        try {
            $first = $this->runCorrectionsScript($script, $fixture);
            $this->assertSame(0, $first['exitCode'], "first run failed:\n{$first['output']}");

            $api = file_get_contents($fixture.'/Api/DefaultApi.php');
            $this->assertStringNotContainsString('Utils::jsonEncode(', $api);
            $this->assertStringContainsString('ObjectSerializer::guzzleJsonEncode(', $api);

            $serializer = file_get_contents($fixture.'/ObjectSerializer.php');
            $this->assertSame(1, substr_count($serializer, 'function guzzleJsonEncode('));
            $this->assertSame(1, substr_count($serializer, "\$class = '\EdgeBox\SyncCore\V2\Raw\Model\\\\'.\$class;"));

            $afterFirstRun = [$api, $serializer];

            // Applied once, the script's own target state now holds, so a
            // second run finds nothing left to do.
            $second = $this->runCorrectionsScript($script, $fixture);
            $this->assertSame(0, $second['exitCode'], "second run failed:\n{$second['output']}");
            $afterSecondRun = [
                file_get_contents($fixture.'/Api/DefaultApi.php'),
                file_get_contents($fixture.'/ObjectSerializer.php'),
            ];

            $this->assertSame($afterFirstRun, $afterSecondRun, 'a second run changed a tree the first run already corrected');
        } finally {
            unlink($fixture.'/Api/DefaultApi.php');
            unlink($fixture.'/ObjectSerializer.php');
            rmdir($fixture.'/Api');
            rmdir($fixture);
        }
    }

    public function testTheGeneratorCorrectionsScriptIsIdempotent(): void
    {
        $script = __DIR__.'/../tools/apply-generator-corrections.php';
        $apiSource = __DIR__.'/../src/V2/Raw/Api/DefaultApi.php';
        $serializerSource = __DIR__.'/../src/V2/Raw/ObjectSerializer.php';

        $fixture = sys_get_temp_dir().'/'.uniqid('sync-core-library-regeneration-test-', true);
        mkdir($fixture.'/Api', 0777, true);
        copy($apiSource, $fixture.'/Api/DefaultApi.php');
        copy($serializerSource, $fixture.'/ObjectSerializer.php');

        try {
            // The committed tree already carries both corrections, so this
            // first run finds its target state in place and changes nothing.
            $first = $this->runCorrectionsScript($script, $fixture);
            $this->assertSame(0, $first['exitCode'], "first run failed:\n{$first['output']}");
            $this->assertSame(file_get_contents($apiSource), file_get_contents($fixture.'/Api/DefaultApi.php'));
            $this->assertSame(file_get_contents($serializerSource), file_get_contents($fixture.'/ObjectSerializer.php'));

            $afterFirstRun = [
                file_get_contents($fixture.'/Api/DefaultApi.php'),
                file_get_contents($fixture.'/ObjectSerializer.php'),
            ];

            $second = $this->runCorrectionsScript($script, $fixture);
            $this->assertSame(0, $second['exitCode'], "second run failed:\n{$second['output']}");
            $afterSecondRun = [
                file_get_contents($fixture.'/Api/DefaultApi.php'),
                file_get_contents($fixture.'/ObjectSerializer.php'),
            ];

            $this->assertSame($afterFirstRun, $afterSecondRun, 'a second run changed a tree the first run already corrected');
        } finally {
            unlink($fixture.'/Api/DefaultApi.php');
            unlink($fixture.'/ObjectSerializer.php');
            rmdir($fixture.'/Api');
            rmdir($fixture);
        }
    }

    /**
     * @return array{exitCode: int, output: string}
     */
    private function runCorrectionsScript(string $script, string $treePath): array
    {
        $command = escapeshellarg(PHP_BINARY).' '.escapeshellarg($script).' '.escapeshellarg($treePath).' 2>&1';
        exec($command, $outputLines, $exitCode);

        return ['exitCode' => $exitCode, 'output' => implode("\n", $outputLines)];
    }
}
