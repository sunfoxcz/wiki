<?php

namespace App\Presenters;

use App\Forms\UserCreateFormFactory;
use App\Libs\Config;
use FilesystemIterator;
use Nette\Application\UI\Form;

final class UserCreatePresenter extends BasePresenter
{
    /**
     * @var Config
     * @inject
     */
    public $config;

    /**
     * @var UserCreateFormFactory
     * @inject
     */
    public $userCreateFormFactory;

    /**
     * @return Form
     */
    protected function createComponentUserCreateForm()
    {
        $this->userCreateFormFactory->onCreate[] = function ($identity) {
            $this->getUser()->login($identity);
            $this->redirect('Wiki:');
        };

        return $this->userCreateFormFactory->create();
    }

    /**
     * @param \Nette\Application\UI\ComponentReflection $element
     */
    public function checkRequirements($element)
    {
        $restricted = true;

        if (!is_dir($this->config->userDir)) {
            $restricted = false;
        } else {
            $it = new FilesystemIterator($this->config->userDir, FilesystemIterator::SKIP_DOTS);
            if (!iterator_count($it)) {
                $restricted = false;
            }
        }

        if ($restricted) {
            parent::checkRequirements($element);
        }
    }
}
