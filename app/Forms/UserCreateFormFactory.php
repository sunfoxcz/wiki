<?php

namespace App\Forms;

use App\Libs\Security\User;
use App\Libs\Security\UserManager;
use Nette;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;
use Nextras;


/**
 * @method onCreate(User $user)
 */
final class UserCreateFormFactory
{
    use Nette\SmartObject;

    /**
     * @var callable[]
     */
    public $onCreate = [];

    /**
     * @var UserManager
     */
    private $userManager;


    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    public function create(): Form
    {
        $form = new Form;
        $form->setRenderer(new Nextras\Forms\Rendering\Bs3FormRenderer)
            ->getElementPrototype()->novalidate = 'novalidate';

        $form->addText('username', 'Username')
            ->setRequired('Enter your username please.');

        $form->addText('name', 'Full name')
            ->setRequired('Enter your full name please.');

        $form->addPassword('password', 'Password')
            ->setRequired('Enter your password please');

        $form->addPassword('confirmation', 'Password confirmation')
            ->setOmitted(TRUE)
            ->addRule(Form::FILLED, 'Enter password once again for confirmation please.')
            ->addRule(Form::EQUAL, 'Passwords does not match. Try to fill them again please.', $form['password']);

        $form->addSubmit('submit', 'Create user');

        $form->onSuccess[] = [$this, 'formSuccess'];

        return $form;
    }

    public function formSuccess(Form $form, ArrayHash $values)
    {
        $user = $this->userManager->create(
            $values->username,
            $values->name,
            $values->password
        );

        $this->onCreate($user);
    }
}
