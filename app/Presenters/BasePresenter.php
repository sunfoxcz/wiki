<?php

namespace App\Presenters;

use Nette;


abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    /**
     * @var \DK\Menu\UI\IControlFactory
     * @inject
     */
    public $menuFactory;

    /**
     * @return \DK\Menu\UI\Control
     */
    protected function createComponentMenu()
    {
        return $this->menuFactory->create();
    }

    /**
     * Basically just helper for IDE because of @return annotation
     *
     * @return \DK\Menu\Menu
     */
    protected function getMenu()
    {
        return $this['menu']->getMenu();
    }
}
