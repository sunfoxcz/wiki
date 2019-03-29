<?php declare(strict_types=1);

namespace App\Presenters;

use Carrooi\Menu\IMenu;
use Carrooi\Menu\MenuContainer;
use Carrooi\Menu\UI\IMenuComponentFactory;
use Carrooi\Menu\UI\MenuComponent;
use Nette;

/**
 * @property-read \Nette\Bridges\ApplicationLatte\Template|\stdClass $template
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    /**
     * @var MenuContainer
     * @inject
     */
    public $menuContainer;

    /**
     * @var IMenuComponentFactory
     * @inject
     */
    public $menuFactory;

    /**
     * @param \Nette\Application\UI\ComponentReflection $element
     */
    public function checkRequirements($element)
    {
        parent::checkRequirements($element);

        if (!$this->getUser()->isLoggedIn() && $this->getName() !== 'Sign') {
            $this->redirect('Sign:in');
        }
    }

    /**
     * @return MenuComponent
     */
    protected function createComponentMenu()
    {
        return $this->menuFactory->create('default');
    }

    /**
     * @return IMenu
     */
    protected function getMenu()
    {
        return $this->menuContainer->getMenu('default');
    }
}
