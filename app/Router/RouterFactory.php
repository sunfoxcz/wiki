<?php declare(strict_types=1);

namespace App;

use Nette\Application\IRouter;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;
use Nette\StaticClass;

final class RouterFactory
{
    use StaticClass;

    public static function createRouter(): IRouter
    {
        $router = new RouteList;

        $router[] = new Route('wiki[/<page .+>]', 'Wiki:default');
        $router[] = new Route('sign/<action>', 'Sign:in');
        $router[] = new Route('user/create', 'UserCreate:default');
        $router[] = new Route('', 'Wiki:default', Route::ONE_WAY);

        return $router;
    }
}
