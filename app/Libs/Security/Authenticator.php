<?php declare(strict_types=1);

namespace App\Libs\Security;

use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\Passwords;

final class Authenticator implements IAuthenticator
{
    /**
     * @var UserManager
     */
    private $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @throws AuthenticationException
     */
    public function authenticate(array $credentials): User
    {
        [$username, $password] = $credentials;

        try {
            $data = $this->userManager->getData($username);
            $user = $this->userManager->getUser($username);
        } catch (UserNotFoundException $e) {
            throw new AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);
        }

        if (!Passwords::verify($password, $data['password'])) {
            throw new AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);
        }

        if (!Passwords::needsRehash($data['password'])) {
            $this->userManager->changePassword($username, $password);
        }

        return $user;
    }
}
