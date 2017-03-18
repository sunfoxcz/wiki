<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;
use Nextras;


/**
 * @method onLoggedIn(Nette\Security\User $user)
 */
final class SignInFormFactory
{
    use Nette\SmartObject;

    /**
     * @var callable[]
     */
    public $onLoggedIn = [];

    /**
     * @var Nette\Security\User
     */
    private $user;

    /**
     * @param Nette\Security\User $user
     */
    public function __construct(Nette\Security\User $user)
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

    /**
     * @param Form      $form
     * @param ArrayHash $values
     */
    public function formSuccess(Form $form, ArrayHash $values)
    {
        if ($values->remember) {
            $this->user->setExpiration('14 days', FALSE);
        } else {
            $this->user->setExpiration('20 minutes', TRUE);
        }

        try {
            $this->user->login($values->username, $values->password);
            $this->onLoggedIn($this->user);

        } catch (Nette\Security\AuthenticationException $e) {
            $form->addError($e->getMessage());
        }
    }
}
