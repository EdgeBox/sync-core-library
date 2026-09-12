<?php

declare(strict_types=1);

/**
 * Rewrites a freshly generated src/V2/Raw tree with the two corrections the
 * openapi-generator PHP output needs, and asserts the target state
 * afterward. The regeneration workflow, .github/workflows/regenerate-client.yml,
 * runs this immediately after copying the generation over the committed
 * client and before the style formatter.
 *
 * Correction 1: the generator calls \GuzzleHttp\Utils::jsonEncode() at every
 * request-body call site in Api/DefaultApi.php. That method exists only in
 * Guzzle 7, while the package supports Guzzle 6 as well, so every call site
 * is rewritten to ObjectSerializer::guzzleJsonEncode(), a helper (added to
 * ObjectSerializer.php by this same script) that picks the encoder whichever
 * installed Guzzle version provides.
 *
 * Correction 2: ObjectSerializer::deserialize() is handed model names without
 * their namespace, which then resolve against the global namespace and are
 * not found. A bare class name is prefixed with the model namespace before
 * the enum check, where a bare enum name has to resolve.
 *
 * @see https://github.com/OpenAPITools/openapi-generator/issues/3136
 *
 * Both transforms are patterns over the generated code, never a line offset,
 * and both are idempotent: run again on an already-corrected tree, they find
 * their target state already in place and make no further change. Every
 * failure mode - an anchor missing, an anchor matching more than once, or the
 * asserted target state not holding after the rewrite - exits non-zero with
 * an explanation instead of leaving a half-corrected tree.
 *
 * Usage: php apply-generator-corrections.php <path to a src/V2/Raw directory>
 */

function abort(string $message): never
{
    fwrite(STDERR, 'apply-generator-corrections: '.$message."\n");
    exit(1);
}

function readOrDie(string $path): string
{
    $code = file_get_contents($path);
    if (false === $code) {
        abort("cannot read {$path}");
    }

    return $code;
}

function writeOrDie(string $path, string $code): void
{
    if (false === file_put_contents($path, $code)) {
        abort("cannot write {$path}");
    }
}

$raw = rtrim($argv[1] ?? '', '/');
if ('' === $raw || !is_dir($raw)) {
    fwrite(STDERR, "usage: php apply-generator-corrections.php <path to a src/V2/Raw directory>\n");
    exit(1);
}

// --- Correction 1: route every request-body encoding call site through the
// version-independent helper. ---
$apiPath = $raw.'/Api/DefaultApi.php';
$api = readOrDie($apiPath);

$directCallSites = substr_count($api, '\GuzzleHttp\Utils::jsonEncode(');
$alreadyRouted = str_contains($api, 'ObjectSerializer::guzzleJsonEncode(');
if (0 === $directCallSites && !$alreadyRouted) {
    abort("no request-body encoding call site, direct or routed, in {$apiPath}; the generator output changed shape and the pattern needs revisiting.");
}
if ($directCallSites > 0) {
    writeOrDie($apiPath, str_replace('\GuzzleHttp\Utils::jsonEncode(', 'ObjectSerializer::guzzleJsonEncode(', $api));
    echo "routed {$directCallSites} request-body encoding call site(s) through ObjectSerializer::guzzleJsonEncode()\n";
} else {
    echo "request-body encoding call sites already route through ObjectSerializer::guzzleJsonEncode()\n";
}

// --- ObjectSerializer.php carries both the helper correction 1 routes
// through and correction 2's namespace guard. ---
$serializerPath = $raw.'/ObjectSerializer.php';
$serializer = readOrDie($serializerPath);
$serializerChanged = false;

$helperSignature = 'public static function guzzleJsonEncode($data)';
$helperCount = substr_count($serializer, $helperSignature);
if ($helperCount > 1) {
    abort("guzzleJsonEncode is defined {$helperCount} times in {$serializerPath}, expected at most one.");
}
if (0 === $helperCount) {
    $formatAnchor = "    private static \$dateTimeFormat = \\DateTime::ATOM;\n\n";
    $anchorCount = substr_count($serializer, $formatAnchor);
    if (1 !== $anchorCount) {
        abort("the date format declaration matches {$anchorCount} time(s) in {$serializerPath}, expected exactly one; the generator output changed shape.");
    }
    $helper = <<<'PHP'
    /**
     * Pass on to the correct json encoder depending on the used Guzzle version.
     *
     * @param mixed $data
     *
     * @return string
     */
    public static function guzzleJsonEncode($data)
    {
        return class_exists('\GuzzleHttp\Utils') && method_exists('\GuzzleHttp\Utils', 'jsonEncode') ? \GuzzleHttp\Utils::jsonEncode($data) : \GuzzleHttp\json_encode($data);
    }


PHP;
    $serializer = str_replace($formatAnchor, $formatAnchor.$helper, $serializer);
    $serializerChanged = true;
    echo "added ObjectSerializer::guzzleJsonEncode()\n";
} else {
    echo "ObjectSerializer::guzzleJsonEncode() already defined\n";
}

$namespaceGuard = "if (!class_exists(\$class) && '\\\\' !== substr(\$class, 0, 1)) {";
$guardCount = substr_count($serializer, $namespaceGuard);
if ($guardCount > 1) {
    abort("the deserialize() namespace guard appears {$guardCount} times in {$serializerPath}, expected at most one.");
}
if (0 === $guardCount) {
    $enumAnchor = "        if (method_exists(\$class, 'getAllowableEnumValues')) {\n";
    $anchorCount = substr_count($serializer, $enumAnchor);
    if (1 !== $anchorCount) {
        abort("the enum guard in deserialize() matches {$anchorCount} time(s) in {$serializerPath}, expected exactly one; the generator output changed shape.");
    }
    $guard = <<<'PHP'
        // @see https://github.com/OpenAPITools/openapi-generator/issues/3136
        if (!class_exists($class) && '\\' !== substr($class, 0, 1)) {
            $class = '\EdgeBox\SyncCore\V2\Raw\Model\\'.$class;
        }


PHP;
    $serializer = str_replace($enumAnchor, $guard.$enumAnchor, $serializer);
    $serializerChanged = true;
    echo "added the deserialize() namespace guard\n";
} else {
    echo "the deserialize() namespace guard is already in place\n";
}

if ($serializerChanged) {
    writeOrDie($serializerPath, $serializer);
}

// --- Verify the target state. A pattern above can match something other
// than what it was written against, so the state is asserted again from
// disk rather than trusted from the rewrite that ran. ---
$api = readOrDie($apiPath);
if (str_contains($api, 'Utils::jsonEncode(')) {
    abort("{$apiPath} still calls a Guzzle-version-specific encoder directly after the rewrite.");
}
if (!str_contains($api, 'ObjectSerializer::guzzleJsonEncode(')) {
    abort("{$apiPath} routes no call through ObjectSerializer::guzzleJsonEncode() after the rewrite.");
}

$serializer = readOrDie($serializerPath);
$helperCount = substr_count($serializer, $helperSignature);
if (1 !== $helperCount) {
    abort("{$serializerPath} defines guzzleJsonEncode {$helperCount} time(s) after the rewrite, expected exactly one.");
}
$guardCount = substr_count($serializer, $namespaceGuard);
if (1 !== $guardCount) {
    abort("{$serializerPath} carries the deserialize() namespace guard {$guardCount} time(s) after the rewrite, expected exactly one.");
}

echo "verified: no direct Guzzle-version-specific encoder call remains, ObjectSerializer::guzzleJsonEncode() is defined once, the deserialize() namespace guard is in place once.\n";
