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
