<?php

namespace App\Presenters;

use App\Forms\SignInFormFactory;
use App\Libs\Config;
use FilesystemIterator;


final class SignPresenter extends BasePresenter
{
    /**
     * @var Config
     * @inject
     */
    public $config;

    /**
     * @var SignInFormFactory
     * @inject
     */
    public $signInFormFactory;


    public function createComponentSignInForm()
    {
        $this->signInFormFactory->onLoggedIn[] = function($user) {
            $this->redirect('Wiki:');
        };
        return $this->signInFormFactory->create();
    }

    public function actionIn()
    {
        if (!is_dir($this->config->userDir)) {
            $this->redirect('UserCreate:');
        }

        $it = new FilesystemIterator($this->config->userDir, FilesystemIterator::SKIP_DOTS);
        if (!iterator_count($it)) {
            $this->redirect('UserCreate:');
        }
    }

    public function actionOut()
    {
        $this->getUser()->logout(TRUE);
        $this->redirect('Sign:in');
    }
}
