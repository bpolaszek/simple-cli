<?php

namespace BenTools\SimpleCli;

use Colors\Color;

function cli(array $args = null, Color $color = null): SimpleCli
{
    static $cli;
    if (null === $cli) {
        $cli = new SimpleCli($args, $color);
    }

    return $cli;
}

function success(string $message, bool $exit = false, bool $colorize = true): void
{
    cli()->success($message, $exit, $colorize);
}

function error(string $message, bool $exit = false, bool $colorize = true): void
{
    cli()->error($message, $exit, $colorize);
}

function warning(string $message, bool $colorize = true): void
{
    cli()->warning($message, $colorize);
}

function write(string $text, bool $colorize = true): void
{
    cli()->write($text, $colorize);
}

function writeLn(string $text, bool $colorize = true): void
{
    cli()->writeLn($text, $colorize);
}

function newLine(int $nb = 1): void
{
    cli()->newLine($nb);
}

function text(string $string): Color
{
    return cli()->text($string);
}

function ask(string $question, $default = null)
{
    return cli()->ask($question, $default);
}

function askHidden(string $question, $default = null)
{
    return cli()->askHidden($question, $default);
}

function confirm(string $question, $default = 'Y'): bool
{
    return cli()->confirm($question, $default);
}

function getOption($key, $default = null)
{
    return cli()->getOption($key, $default);
}

function getArgument($index, $default = null)
{
    return cli()->getArgument($index, $default);
}
