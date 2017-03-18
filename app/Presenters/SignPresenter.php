<?php

namespace App\Presenters;

use App\Forms\SignInFormFactory;


final class SignPresenter extends BasePresenter
{
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

    public function actionOut()
    {
        $this->getUser()->logout(TRUE);
        $this->redirect('Sign:in');
    }
}
