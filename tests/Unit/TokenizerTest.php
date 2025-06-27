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

it('should throw exception for unexpected charater', function () {
    $tokenizer = new Tokenizer('x');

    expect(fn() => $tokenizer->nextToken())
        ->toThrow(\RuntimeException::class);
});
