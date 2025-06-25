<?php

declare(strict_types=1);

namespace JsonParser;

enum TokenType: string
{
    case BraceOpen = 'BraceOpen';
    case BraceClose = 'BraceClose';
    case BracketOpen = 'BracketOpen';
    case BracketClose = 'BracketClose';
    case String = 'String';
    case Number = 'Number';
    case Colon  = 'Colon';
    case Comma = 'Comma';
    case True = 'True';
    case False = 'False';
    case Null = 'Null';
}