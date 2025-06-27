<?php

declare(strict_types=1);

namespace JsonParser;

use JsonParser\TokenType;

final readonly class Token
{
    public function __construct(
        public TokenType $type,
        public mixed $value,
        public int $line,
        public int $column
    ) {}
}
