<?php

namespace App\Presenters;

use Nette;


/**
 * @property-read \Nette\Bridges\ApplicationLatte\Template|\stdClass $template
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    /**
     * @var \DK\Menu\UI\IControlFactory
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
