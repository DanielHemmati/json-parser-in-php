<?php

declare(strict_types=1);

namespace JsonParser;

use JsonParser\Token;
use JsonParser\TokenType;

class Tokenizer
{
    private string $input;
    private int $pos = 0;
    public int $length;
    // for telling lovely human where the error is
    private int $line = 1;
    private int $column = 1;

    public function __construct(string $json)
    {
        $this->input = $json;
        $this->length = strlen($json);
    }

    public function nextToken(): ?Token
    {
        // skip white space

        // i don't think we ever hit this
        if ($this->pos >= $this->length) {
            return null;
        }

        $char = $this->input[$this->pos];

        return match ($char) {
            '{' => $this->emit(TokenType::BraceOpen),
            '}' => $this->emit(TokenType::BraceOpen)
        };
    }

    private function emit(TokenType $type): Token
    {
        return new Token($type, $this->input[$this->pos++]);
    }

    private function skipWhiteSpace(): void {

    }
}
