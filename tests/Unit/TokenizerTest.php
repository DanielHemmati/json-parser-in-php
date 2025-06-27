<?php

use JsonParser\Tokenizer;
use JsonParser\Token;
use JsonParser\TokenType;

it('should return brace open', function () {
    $tokenizer = new Tokenizer('{');
    $token = $tokenizer->nextToken();
    expect($token)->toBeInstanceOf(Token::class);
    expect($token->type)->toBe(TokenType::BraceOpen);
});

it('should return brace close', function () {
    $tokenizer = new Tokenizer('}');
    $token = $tokenizer->nextToken();
    expect($token)->toBeInstanceOf(Token::class);
    expect($token->type)->toBe(TokenType::BraceClose);
});

it('should tokenize empty object correctly', function () {
    $tokenizer = new Tokenizer('{}');

    $firstToken = $tokenizer->nextToken();
    expect($firstToken)->toBeInstanceOf(Token::class);
    expect($firstToken->type)->toBe(TokenType::BraceOpen);
    expect($firstToken->value)->toBe('{');

    $secondToken = $tokenizer->nextToken();
    expect($secondToken)->toBeInstanceOf(Token::class);
    expect($secondToken->type)->toBe(TokenType::BraceClose);
    expect($secondToken->value)->toBe('}');

    $thirdToken = $tokenizer->nextToken();
    expect($thirdToken)->toBeNull();
});

it('should return bracket open', function () {
    $tokenizer = new Tokenizer('[');
    $token = $tokenizer->nextToken();
    expect($token)->toBeInstanceOf(Token::class);
    expect($token->type)->toBe(TokenType::BracketOpen);
});

it('should return bracket close', function () {
    $tokenizer = new Tokenizer(']');
    $token = $tokenizer->nextToken();
    expect($token)->toBeInstanceOf(Token::class);
    expect($token->type)->toBe(TokenType::BracketClose);
});

it('should tokenize empty array correctly', function () {
    $tokenizer = new Tokenizer('[]');

    $firstToken = $tokenizer->nextToken();
    expect($firstToken)->toBeInstanceOf(Token::class);
    expect($firstToken->type)->toBe(TokenType::BracketOpen);
    expect($firstToken->value)->toBe('[');

    $secondToken = $tokenizer->nextToken();
    expect($secondToken)->toBeInstanceOf(Token::class);
    expect($secondToken->type)->toBe(TokenType::BracketClose);
    expect($secondToken->value)->toBe(']');

    $thirdToken = $tokenizer->nextToken();
    expect($thirdToken)->toBeNull();
});

it('tokenize a simple JSON string', function () {
    $tokenizer = new Tokenizer('"hello"');

    /** @var Token $tok */
    $tok = $tokenizer->nextToken();

    expect($tok)->toBeInstanceOf(Token::class);
    expect($tok->type)->toBe(TokenType::String);
    expect($tok->value)->toBe('hello');

    expect($tokenizer->nextToken())->toBeNull();
});

it('keeps esace sequence intact', function () {
    $json = '"He said: \\"hi\\""'; // -> \"hi\"
    $tokenizer = new Tokenizer($json);

    $tok = $tokenizer->nextToken();

    expect($tok->type)->toBe(TokenType::String);
    expect($tok->value)->toBe('He said: \\"hi\\"');
});


it('handles unicode escape sequence intact', function () {
    $tokenizer = new Tokenizer('"unicode: \\u0041"');

    $tok = $tokenizer->nextToken();

    expect($tok->value)->toBe('unicode: \\u0041');
});

it('throws on unterminated string', function () {
    $tokenizer = new Tokenizer('"no-close');

    expect(fn() => $tokenizer->nextToken())
        ->toThrow(RuntimeException::class, 'Unterminated string literal');
});

it('throws on backslashes at end of input', function () {
    $tokenizer = new Tokenizer('"abc\\');

    expect(fn() => $tokenizer->nextToken())
        ->toThrow(RuntimeException::class, 'Unterminated escape sequence');
});

it('tokenizes a colon', function () {
    $tokenizer = new Tokenizer(':');
    $tok = $tokenizer->nextToken();

    expect($tok)->not()->toBeNull();
    expect($tok->type)->toBe(TokenType::Colon);
    expect($tok->value)->toBe(':');
});

it('tokenizes a comma', function () {
    $tokenizer = new Tokenizer(',');
    $tok = $tokenizer->nextToken();

    expect($tok)->not()->toBeNull();
    expect($tok->type)->toBe(TokenType::Comma);
    expect($tok->value)->toBe(',');
});
