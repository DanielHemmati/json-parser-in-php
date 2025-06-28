<?php

declare(strict_types=1);

use JsonParser\Parser;

// this is the test suite from: https://www.json.org/JSON_checker/

it('parses all JSON.org pass*.json files', function (string $json, $expected) {
    $parsed = (new Parser($json))->parse();
    expect($parsed)->toEqual($expected);
})->with('jsonOrgPass');
