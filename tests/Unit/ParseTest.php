<?php

declare(strict_types=1);

use JsonParser\Parser;

it('should parse a empty json', function () {
    $parser = (new Parser('{}'))->parse();
    expect($parser)->toBe([]);
});

it('parse a json with values', function () {
    $parser = (new Parser('{"name": "Daniel", "x": "daniellhemmati"}'))->parse();
    expect($parser)->toBe([
        'name' => 'Daniel',
        'x' => 'daniellhemmati'
    ]);
});

it('parse a json with nested object', function () {
    $parser = new Parser('{"name": "Daniel", "x": "daniellhemmati", "others": {
        "tech": "php,typescript,python,go",
        "framework": "laravel,next.js,astro"
    }}');
    $result = $parser->parse();
    expect($result)->toBe([
        'name' => 'Daniel',
        'x' => 'daniellhemmati',
        'others' => [
            'tech' => 'php,typescript,python,go',
            'framework' => 'laravel,next.js,astro'
        ]
    ]);
});

it('should parse an empty array correctly', function () {
    $parser = (new Parser('[]'))->parse();
    expect($parser)->toBe([]);
});

it('should parse array with values correctly', function () {
    $parser = (new Parser('["daniel", "hemmati", "php"]'))->parse();
    expect($parser)->toBe(['daniel', 'hemmati', 'php']);
});

it('parse valid json into native php values', function (string $json, $expected) {
    $parser = (new Parser($json))->parse();
    expect($parser)->toEqual($expected);
})->with('validJson');

it('throws RuntimeException on invalid json', function (string $json) {
    (new Parser($json))->parse();
})->with('invalidJson')->throws(RuntimeException::class);

it('should parse quote correctly', function () {
    $parser = (new Parser('{"quote":  "\""}'))->parse();
    expect($parser)->toEqual([
        'quote' => '"'
    ]);
});

it('should parse unicode escape sequences correctly', function () {
    $parser = (new Parser('{"unicode": "\u0123\u4567\u89AB\uCDEF\uabcd\uef4A"}'))->parse();
    expect($parser)->toEqual([
        'unicode' => "\u{0123}\u{4567}\u{89AB}\u{CDEF}\u{abcd}\u{ef4A}"
    ]);
});

// from fail13.json file
it('should fail on json that have a leading 0', function () {
    (new Parser('{"test": 013}'))->parse();
})->throws(RuntimeException::class);

// from fail25.json and fail27.json
it('should fail on un-printable characters', function () {
    (new Parser('["	tab	character	in	string	"]'))->parse();
})->throws(RuntimeException::class);
