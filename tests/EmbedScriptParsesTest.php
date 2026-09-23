<?php

declare(strict_types=1);

namespace EdgeBox\SyncCore\Tests;

use EdgeBox\SyncCore\Interfaces\Embed\IEmbedFeature;
use EdgeBox\SyncCore\Tests\Support\EmbedMarkup;
use EdgeBox\SyncCore\V2\Embed\Embed;
use EdgeBox\SyncCore\V2\Embed\EmbedService;
use PHPUnit\Framework\TestCase;

/**
 * Every inline script an embed writes into a page is parsable JavaScript.
 *
 * The script is assembled by concatenating PHP strings, whole functions among
 * them, so a brace that is one line out of place is a syntax error in the
 * rendered page rather than in this repository. An assertion that names a
 * substring cannot see that: every piece it names is still emitted while the
 * script as a whole no longer parses, and a script element that does not parse
 * runs none of its statements, which stops every embed on the page. So the
 * script is handed to a JavaScript engine and asked to parse it.
 *
 * @internal
 */
final class EmbedScriptParsesTest extends TestCase
{
    public function testTheScriptTheBoxOfAPagesFiguresEmitsParses(): void
    {
        $scripts = $this->inlineScripts($this->box(self::figures()));

        // The box is the one embed that carries the watch, so its script is
        // where a spliced-in function can break the parse.
        $this->assertStringContainsString('remeasureOnUncover();', $scripts[0]);

        $this->assertEveryScriptParses($scripts, 'the box of a page\'s figures');
    }

    public function testTheScriptStaysOneScriptAndParsesWhateverAPersonWrote(): void
    {
        $figures = self::figures();
        $figures['tags'] = [
            ['key' => 'pricing', 'name' => '</script><script>window.stop()</script>'],
            ['key' => 'onboarding', 'name' => "a quote \" a backslash \\ and a line\nbreak"],
        ];

        $scripts = $this->inlineScripts($this->box($figures));

        // What a person wrote is escaped rather than closing the element it
        // travels in, so the page still receives one script and that one
        // parses. Both halves are needed: an escape that broke the syntax
        // would keep the element whole and stop every embed on the page.
        $this->assertCount(1, $scripts);
        $this->assertEveryScriptParses($scripts, 'a box carrying prose a person wrote');
    }

    public function testTheScriptAPageEmitsParses(): void
    {
        $this->assertEveryScriptParses($this->inlineScripts($this->markupForSize('page')), 'a page');
    }

    public function testTheScriptALineEmitsParses(): void
    {
        $this->assertEveryScriptParses($this->inlineScripts($this->markupForSize('line')), 'a line');
    }

    /**
     * Hand every script to the engine and let it report what it cannot parse.
     *
     * @param string[] $scripts
     */
    private function assertEveryScriptParses(array $scripts, string $what): void
    {
        $this->assertNotEmpty($scripts, $what.' emits no inline script at all, so nothing here is proven');

        foreach ($scripts as $index => $script) {
            [$status, $output] = $this->parse($script);

            $this->assertSame(0, $status, 'script '.($index + 1).' of '.$what.' is not parsable JavaScript: '.$output);
        }
    }

    /**
     * Parse one script with node, and report its exit code and what it said.
     *
     * A script element is parsed under the script grammar, and neither a
     * module nor a CommonJS file is: a file checked as either is wrapped in
     * a function first, so a statement a page refuses — a `return` left at
     * the top level by a brace one line out of place, which is exactly the
     * slip this guards — passes there. Compiling the source as a script is
     * what refuses it, so that is what is done.
     *
     * node is what parses the script; a run without it fails rather than
     * passing quietly, because a gate nobody notices is off proves nothing.
     *
     * @return array{0: int, 1: string}
     */
    private function parse(string $script): array
    {
        // tempnam reserves a name; the script is written beside it under that
        // name with a .js suffix, which decides no grammar - the engine is
        // handed the source and told to compile it as a script - and is kept
        // only because it makes a report of node's own name a JavaScript
        // file. Both the reservation and the file go below, whatever happens
        // in between.
        $reserved = tempnam(sys_get_temp_dir(), 'embed-script-');
        $this->assertIsString($reserved);
        $path = $reserved.'.js';

        $program = 'const fs = require("fs"), vm = require("vm");'
            .' new vm.Script(fs.readFileSync(process.argv[1], "utf8"), {filename: process.argv[1]});';

        $output = [];
        $status = 1;

        try {
            $this->assertNotFalse(file_put_contents($path, $script));

            exec('node -e '.escapeshellarg($program).' '.escapeshellarg($path).' 2>&1', $output, $status);
        } finally {
            if (is_file($path)) {
                unlink($path);
            }

            if (is_file($reserved)) {
                unlink($reserved);
            }
        }

        return [$status, implode("\n", $output)];
    }

    /**
     * Every script element of the markup that carries statements of its own.
     *
     * The resizer is loaded from a source and has an empty body; everything
     * else a render emits inline is a script this library assembled.
     *
     * @return string[]
     */
    private function inlineScripts(string $html): array
    {
        $this->assertNotFalse(preg_match_all('@<script([^>]*)>(.*?)</script>@s', $html, $matches, PREG_SET_ORDER));

        $scripts = [];
        foreach ($matches as $match) {
            if (false !== strpos($match[1], 'src=') || '' === trim($match[2])) {
                continue;
            }

            $scripts[] = $match[2];
        }

        return $scripts;
    }

    /**
     * The markup the box of a page's figures renders.
     */
    private function box(array $figures): string
    {
        return $this->render(
            (new EmbedService(EmbedMarkup::core()))->pageFigures($figures)
        );
    }

    /**
     * A page whose every figure arrived.
     */
    private static function figures(): array
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

    /**
     * The markup of one embed rendered at the given frame size.
     */
    private function markupForSize(string $size): string
    {
        return $this->render((new EmbedService(EmbedMarkup::core()))->updateStatusBox(['embedSize' => $size]));
    }

    /**
     * The markup one embed renders, exactly as a page receives it.
     */
    private function render(IEmbedFeature $embed): string
    {
        // The resizer element is emitted once per page, so every render here
        // is taken as the first one.
        Embed::$iframeResizerAdded = '';

        return (string) $embed->run()->getRenderedHtml();
    }
}
