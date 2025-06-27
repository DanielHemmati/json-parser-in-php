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
            '[' => $this->emit(TokenType::BracketOpen),
            ']' => $this->emit(TokenType::BracketClose),
            ':' => $this->emit(TokenType::Colon),
            ',' => $this->emit(TokenType::Comma),
            '"' => $this->readString(),
            default => $this->readNumberOrLiteral()
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

    private function readString(): Token
    {
        $startLine = $this->line;
        $startColumn = $this->column;

        $this->advanceCurosr();

        $value = '';
        while ($this->pos < $this->length) {
            $ch = $this->input[$this->pos];

            if ($ch === '\\') {
                $value .= $ch;
                $this->advanceCurosr();
                if ($this->pos >= $this->length) {
                    throw new \RuntimeException("Unterminated escape sequence at end of input (started {$startLine}:{$startColumn}");
                }
                $value .= $this->input[$this->pos];
                $this->advanceCurosr();
                continue;
            }

            if ($ch === '"') {
                break;
            }

            $value .= $ch;
            $this->advanceCurosr();
        }

        if ($this->pos >= $this->length) {
            throw new \RuntimeException("Unterminated string literal starting at {$startLine}:{$startColumn}");
        }

        $this->advanceCurosr();

        return new Token(TokenType::String, $value, $startLine, $startColumn);
    }

    private function readNumberOrLiteral(): Token
    {
        $char = $this->input[$this->pos];

        // if the number if sth like "-7" and you use is_numeric it will call 
        // the readLiteral intead of readNumber, that's why we use 
        // ctype_digit and we check for the negative using '-"
        if (ctype_digit($char) || $char === '-' || $char === '.') {
            return $this->readNumber();
        }

        return $this->readLiteral();
    }

    private function readNumber(): Token
    {
        $startLine = $this->line;
        $startColumn = $this->column;
        $start = $this->pos;

        while ($this->pos < $this->length) {
            $ch = $this->input[$this->pos];
            if (
                !ctype_digit($ch) &&
                $ch !== '.' &&
                $ch !== '-' &&
                $ch !== 'e' &&
                $ch !== 'E' &&
                $ch !== '+'
            ) {
                break;
            }
            $this->advanceCurosr();
        }

        $literal = substr($this->input, $start, $this->pos - $start);

        if (is_numeric($literal)) {
            return new Token(TokenType::Number, +$literal, $startLine, $startColumn);
        }
        throw new \RuntimeException("Invalid number '$literal' at {$startLine}:{$startColumn}");
    }

    private function readLiteral(): Token
    {
        $startLine = $this->line;
        $startColumn = $this->column;
        $start = $this->pos;

        while ($this->pos < $this->length && ctype_alpha($this->input[$this->pos])) {
            $this->advanceCurosr();
        }

        $literal = substr($this->input, $start, $this->pos - $start);

        return match ($literal) {
            'true' => new Token(TokenType::True, true, $startLine, $startColumn),
            'false' => new Token(TokenType::False, false, $startLine, $startColumn),
            'null' => new Token(TokenType::Null, null, $startLine, $startColumn),
            default => throw new \RuntimeException("Unexptected literal '$literal' at {$startLine}:{$startColumn}")
        };
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

        // $this->pos++ will move the position forward
        $ch = $this->input[$this->pos++];

        if ($ch === "\n") {
            $this->line++;
            $this->column = 1;
        } else {
            $this->column++;
        }
    }
}
