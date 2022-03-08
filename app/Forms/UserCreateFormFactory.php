<?php declare(strict_types=1);

namespace App\Forms;

use App\Libs\Security\User;
use App\Libs\Security\UserExistsException;
use App\Libs\Security\UserManager;
use Nette\Application\UI\Form;
use Nette\Security\Passwords;
use Nette\SmartObject;
use Nette\Utils\ArrayHash;
use Nextras\FormsRendering\Renderers\Bs3FormRenderer;

/**
 * @method onCreate(User $user)
 */
final class UserCreateFormFactory
{
    use SmartObject;

    /**
     * @var callable[]
     */
    public $onCreate = [];

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var Passwords
     */
    private $passwords;

    public function __construct(UserManager $userManager, Passwords $passwords)
    {
        $this->userManager = $userManager;
        $this->passwords = $passwords;
    }

    public function create(): Form
    {
        $form = new Form;
        $form->setRenderer(new Bs3FormRenderer)
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

    public function formSuccess(Form $form, ArrayHash $values): void
    {
        try {
            $password = $this->passwords->hash($values->password);
            $user = $this->userManager->create($values->username, $values->name, $password);
        } catch (UserExistsException $e) {
            $form->addError("User with username {$values->username} doesn't exist.");
            return;
        }

        $this->onCreate($user);
    }
}
