<?php declare(strict_types=1);

namespace App;

use Nette\Bootstrap\Configurator;

final class Bootstrap
{
    public static function boot(): Configurator
    {
        $configurator = new Configurator;

        if (getenv('NETTE_DEVEL') === '1') {
            $configurator->setDebugMode(TRUE);
        }

        $configurator->setTimeZone('Europe/Prague');
        $configurator->enableTracy(__DIR__ . '/../log');
        $configurator->setTempDirectory(__DIR__ . '/../temp');

        $configurator->addConfig(__DIR__ . '/Config/config.neon');
        $configurator->addConfig(__DIR__ . '/Config/config.local.neon');

        return $configurator;
    }
}
