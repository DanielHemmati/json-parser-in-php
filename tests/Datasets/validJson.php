<?php

declare(strict_types=1);

dataset('validJson', [
    'empty‑object' => ['{}', []],
    'empty‑array'  => ['[]', []],
    'flat‑object'  => ['{"a":1,"b":true}', ['a' => 1, 'b' => true]],
    'nested‑array' => ['{"arr":[1,null,false]}', ['arr' => [1, null, false]]],
    'mixed'        => ['[{"x":10}, false]', [['x' => 10], false]],
]);
