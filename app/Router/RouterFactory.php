<?php declare(strict_types=1);

namespace App;

use Nette;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

final class RouterFactory
{
    use Nette\StaticClass;

    /**
     * @return Nette\Application\IRouter
     */
    public static function createRouter()
    {
        $router = new RouteList;

        $router[] = new Route('wiki[/<page .+>]', 'Wiki:default');
        $router[] = new Route('sign/<action>', 'Sign:in');
        $router[] = new Route('user/create', 'UserCreate:default');
        $router[] = new Route('', 'Wiki:default', Route::ONE_WAY);

        return $router;
    }
}
