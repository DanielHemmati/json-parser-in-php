<?php

declare(strict_types=1);

// use JsonParser\Tokenizer;
// use JsonParser\Token;
// use JsonParser\TokenType;
use JsonParser\Parser;

it('should parse a empty json', function () {
    $parser = new Parser('{}');
    $result = $parser->parse();
    expect($result)->toBe([]);
});

it('parse a json with values', function () {
    $parser = new Parser('{"name": "Daniel", "x": "daniellhemmati"}');
    $result = $parser->parse();
    expect($result)->toBe([
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
