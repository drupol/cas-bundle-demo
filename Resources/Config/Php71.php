<?php

declare(strict_types=1);

use drupol\PhpCsFixerConfigsPhp\Config\Php71;

$config = new Php71();

$config
    ->getFinder()
    ->exclude(['build', 'libraries', 'node_modules', 'vendor', 'var']);

return $config;
