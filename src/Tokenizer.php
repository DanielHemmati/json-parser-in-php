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
        $this->skipWhiteSpace();

        // i don't think we ever hit this
        if ($this->pos >= $this->length) {
            return null;
        }

        $char = $this->input[$this->pos];

        return match ($char) {
            '{' => $this->emit(TokenType::BraceOpen),
            '}' => $this->emit(TokenType::BraceClose),
            default => throw new \RuntimeException("Unexptcte character '{$char}' at {$this->line}:{$this->column}")
        };
    }

    private function emit(TokenType $type): Token
    {
        $token = new Token(
            $type,
            $this->input[$this->pos],
            $this->line,
            $this->column
        );
        $this->advanceCurosr();
        return $token;
    }

    /**
     * Skip RFC-8259 insignificant whitespace (space, tab, LF, CR).
     * @link https://datatracker.ietf.org/doc/html/rfc8259
     */
    private function skipWhiteSpace(): void
    {
        while ($this->pos < $this->length) {
            $ch = $this->input[$this->pos];

            if ($ch !== ' ' && $ch !== "\t" && $ch !== "\n" && $ch !== "\r") {
                return;
            }

            $this->advanceCurosr();
        }
    }

    private function advanceCurosr(): void
    {
        /**
         * this is me just being defensive. b/c nextToken method already check this 
         * as well
         **/
        if ($this->pos >= $this->length) {
            return;
        }

        $ch = $this->input[$this->pos++];

        if ($ch === "\n") {
            $this->line++;
            $this->column = 1;
        } else {
            $this->column++;
        }
    }
}
