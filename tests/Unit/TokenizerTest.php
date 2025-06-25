<?php

use JsonParser\Tokenizer;
use JsonParser\Token;
use JsonParser\TokenType;

it('it should return brace open', function () {
    $tokenizer = new Tokenizer('{');
    $token = $tokenizer->nextToken();
    expect($token)->toBeInstanceOf(Token::class);
    expect($token->type)->toBe(TokenType::BraceOpen);
});