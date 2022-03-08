<?php declare(strict_types=1);

namespace App\Router;

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

        $router->addRoute('wiki[/<page .+>]', 'Wiki:default');
        $router->addRoute('sign/<action>', 'Sign:in');
        $router->addRoute('user/create', 'UserCreate:default');
        $router->addRoute('', 'Wiki:default', Route::ONE_WAY);

        return $router;
    }
}
