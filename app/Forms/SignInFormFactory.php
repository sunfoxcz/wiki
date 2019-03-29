<?php declare(strict_types=1);

namespace App\Forms;

use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Security\User;
use Nette\SmartObject;
use Nette\Utils\ArrayHash;
use Nextras\Forms\Rendering\Bs3FormRenderer;

/**
 * @method onLoggedIn(User $user)
 */
final class SignInFormFactory
{
    use SmartObject;

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

    public function create(): Form
    {
        $form = new Form;
        $form->setRenderer(new Bs3FormRenderer)
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

    public function formSuccess(Form $form, ArrayHash $values): void
    {
        if ($values->remember) {
            $this->user->setExpiration('14 days', FALSE);
        } else {
            $this->user->setExpiration('20 minutes', TRUE);
        }

        try {
            $this->user->login($values->username, $values->password);
            $this->onLoggedIn($this->user);
        } catch (AuthenticationException $e) {
            $form->addError($e->getMessage());
        }
    }
}
