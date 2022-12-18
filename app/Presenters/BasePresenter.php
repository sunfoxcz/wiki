<?php declare(strict_types=1);

namespace App\Presenters;

use Contributte\MenuControl\IMenu;
use Contributte\MenuControl\MenuContainer;
use Contributte\MenuControl\UI\MenuComponent;
use Contributte\MenuControl\UI\MenuComponentFactory;
use Nette\Application\AbortException;
use Nette\Application\UI\ComponentReflection;
use Nette\Application\UI\Presenter;

/**
 * @property-read \Nette\Bridges\ApplicationLatte\Template|\stdClass $template
 */
abstract class BasePresenter extends Presenter
{
    /**
     * @var MenuContainer
     * @inject
     */
    public $menuContainer;

    /**
     * @var MenuComponentFactory
     * @inject
     */
    public $menuFactory;

    /**
     * @param ComponentReflection $element
     * @throws AbortException
     */
    public function checkRequirements($element): void
    {
        parent::checkRequirements($element);

        if (!$this->getUser()->isLoggedIn() && $this->getName() !== 'Sign') {
            $this->redirect('Sign:in');
        }
    }

    protected function createComponentMenu(): MenuComponent
    {
        return $this->menuFactory->create('default');
    }

    protected function getMenu(): IMenu
    {
        return $this->menuContainer->getMenu('default');
    }
}
