<?php

declare(strict_types=1);

dataset('invalidJson', [
    'mismatched‑bracket'   => ['{]'],               // wrong closing token
    'missing‑brace'        => ['{"a":1'],          // unterminated object
    'trailing‑data'        => ['{"a":1} true'],    // two top‑level values
    'unquoted‑key'         => ['{a:1}'],            // key not string
    'array‑missing‑comma'  => ['[1 2]'],            // no comma between elems
]);
