<?php

declare(strict_types=1);

namespace JsonParser;

use JsonParser\Token;
use JsonParser\Tokenizer;

class Parser
{
    private Tokenizer $tokenizer;
    private ?Token $current = null;

    public function __construct(string $json)
    {
        $this->tokenizer = new Tokenizer($json);
        $this->advance();
    }

    public function parse(): mixed
    {
        $value = $this->parseValue();

        // imagine you have sth like "true false" as json.
        // true is correct and after that we exepect
        // EOF but we get false, which is wrong
        if ($this->current !== null) {
            throw $this->error("Trailing data after top-level value");
        }

        return $value;
    }

    private function advance()
    {
        $this->current = $this->tokenizer->nextToken();
    }

    private function consume(TokenType ...$types): Token
    {
        if ($this->current === null) {
            throw $this->error("Unexpected end of input");
        }

        foreach ($types as $type) {
            if ($this->current->type === $type) {
                $tok = $this->current;
                $this->advance();
                return $tok;
            }
        }

        $expected = implode("|", array_map(fn($t) => $t->value, $types));
        throw $this->error("Expected $expected, got {$this->current->type->value}");
    }

    private function error(string $msg): \RuntimeException
    {
        // if there is a token give the line and column for hooman
        if ($this->current) {
            return new \RuntimeException("$msg at {$this->current->line}:{$this->current->column}");
        }
        // if not (means we $this->current is null) then just throw an error
        return new \RuntimeException($msg);
    }


    private function parseValue(): mixed
    {
        if ($this->current === null) {
            throw $this->error("Unexpected end of input while parsing value");
        }

        return match ($this->current->type) {
            TokenType::String      => $this->consume(TokenType::String)->value,
            TokenType::Number      => $this->consume(TokenType::Number)->value,
            TokenType::True        => $this->consume(TokenType::True)->value,
            TokenType::False       => $this->consume(TokenType::False)->value,
            TokenType::Null        => $this->consume(TokenType::Null)->value,
            TokenType::BraceOpen   => $this->parseObject(),
            TokenType::BracketOpen => $this->parseArray(),
            default                => throw $this->error("Unexpected token {$this->current->type->value} while parsing value")
        };
    }

    private function parseObject(): array
    {
        $this->consume(TokenType::BraceOpen);
        $object = [];

        if ($this->current?->type === TokenType::BraceClose) {
            $this->advance();
            return $object;
        }

        while (true) {
            $keyTok = $this->consume(TokenType::String); // json must start with "
            $this->consume(TokenType::Colon); // then consume and advance
            $value = $this->parseValue(); // get the value by going throw parseValue again
            $object[$keyTok->value] = $value;

            if ($this->current?->type === TokenType::Comma) {
                $this->advance();
                continue;
            }

            if ($this->current?->type === TokenType::BraceClose) {
                $this->advance();
                break;
            }

            throw $this->error('Expected , or } in object');
        }

        return $object;
    }

    private function parseArray(): array
    {
        $this->consume(TokenType::BracketOpen);
        $list = [];

        if ($this->current?->type === TokenType::BracketClose) {
            $this->advance();
            return $list;
        }

        while (true) {
            $list[] = $this->parseValue();

            if ($this->current?->type === TokenType::Comma) {
                $this->advance();
                continue;
            }

            if ($this->current?->type === TokenType::BracketClose) {
                $this->advance();
                break;
            }

            throw $this->error('Expected , or ] in array');
        }

        return $list;
    }
}
