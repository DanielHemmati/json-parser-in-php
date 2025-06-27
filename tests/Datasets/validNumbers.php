<?php

dataset('validNumbers', [
    'int'               => ['42',          42],
    'float-leading-dot' => ['.42',        .42],
    'neg-int'           => ['-7',          -7],
    'float'             => ['3.14',      3.14],
    'exp-pos'           => ['1e3',     1000.0],
    'exp-neg'           => ['5.5e-1',    0.55],
]);
