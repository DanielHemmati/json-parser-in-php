<?php

declare(strict_types=1);

namespace JsonParser;

require_once __DIR__ . '/../vendor/autoload.php';

use JsonParser\Parser;

// Just a playground to test stuff
$json = '{"name": "Daniel", "age": "guess", "skills": ["PHP", "Python", "Typescript", "Go"], "active": true, "profile": null}';

echo "Original JSON:\n";
echo $json . "\n\n";

try {
    $parser = new Parser($json);
    $result = $parser->parse();

    echo "Parsed result:\n";
    var_dump($result);
} catch (\RuntimeException $e) {
    echo "Error parsing JSON: " . $e->getMessage() . "\n";
}

dump(hexdec("\ud83d") >= 0xD800);
dump(hexdec("\ud83d") <= 0xDBFF);
