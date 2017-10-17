<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;
use Nette\Security\User;
use Nette\Utils\ArrayHash;
use Nextras;

/**
 * @method onLoggedIn(User $user)
 */
final class SignInFormFactory
{
    use Nette\SmartObject;

    /**
     * @var callable[]
     */
    public $onLoggedIn = [];

    /**
     * @var User
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return Form
     */
    public function create()
    {
        $form = new Form;
        $form->setRenderer(new Nextras\Forms\Rendering\Bs3FormRenderer)
            ->getElementPrototype()->novalidate = 'novalidate';

        $form->addText('username', 'Username')
            ->setRequired('Enter your username please.');

        $form->addPassword('password', 'Password')
            ->setRequired('Enter your password please');

        $form->addCheckbox('remember', 'Stay logged in');

        $form->addSubmit('submit', 'Sign in');

        $form->onSuccess[] = [$this, 'formSuccess'];
        return $form;
    }

    public function formSuccess(Form $form, ArrayHash $values)
    {
        if ($values->remember) {
            $this->user->setExpiration('14 days', false);
        } else {
            $this->user->setExpiration('20 minutes', true);
        }

        try {
            $this->user->login($values->username, $values->password);
            $this->onLoggedIn($this->user);
        } catch (Nette\Security\AuthenticationException $e) {
            $form->addError($e->getMessage());
        }
    }
}
