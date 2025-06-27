<?php

use JsonParser\TokenType;

dataset('validLiterals', [
    'true'  => ['true',  true,  TokenType::True],
    'false' => ['false', false, TokenType::False],
    'null'  => ['null',  null,  TokenType::Null],
]);
