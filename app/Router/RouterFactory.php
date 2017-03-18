<?php

namespace App;

use Nette;
use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;


final class RouterFactory
{
    use Nette\StaticClass;

    /**
     * @return Nette\Application\IRouter
     */
    public static function createRouter()
    {
        $router = new RouteList;

        $router[] = new Route('wiki[/<page [A-Za-z0-9_\-/]+>]', 'Wiki:default');
        $router[] = new Route('sign/<action>', 'Sign:in');
        $router[] = new Route('', 'Wiki:default', Route::ONE_WAY);

        return $router;
    }
}
