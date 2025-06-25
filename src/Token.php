<?php

declare(strict_types=1);

namespace JsonParser;

use JsonParser\TokenType;

final class Token
{
    public function __construct(
        public readonly TokenType $type,
        public readonly mixed $value,
    ) {}
}
