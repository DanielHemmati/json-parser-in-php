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

    // first token
    $token1 = $tokenizer->nextToken();
    expect($token1)->toBeInstanceOf(Token::class);
    expect($token1->type)->toBe(TokenType::BraceOpen);
    expect($token1->value)->toBe('{');

    // second token
    $token2 = $tokenizer->nextToken();
    expect($token2)->toBeInstanceOf(Token::class);
    expect($token2->type)->toBe(TokenType::BraceClose);
    expect($token2->value)->toBe('}');
    

});

it('should throw exception for unexpected charater', function () {
    $tokenizer = new Tokenizer('x');

    expect(fn() => $tokenizer->nextToken())
        ->toThrow(\RuntimeException::class);
});