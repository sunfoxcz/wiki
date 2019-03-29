<?php declare(strict_types=1);

namespace App\Libs\Security;

use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\IIdentity;
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
     * Performs an authentication against e.g. database.
     * and returns IIdentity on success or throws AuthenticationException
     * @return IIdentity
     * @throws AuthenticationException
     */
    public function authenticate(array $credentials)
    {
        list($username, $password) = $credentials;

        try {
            $data = $this->userManager->getData($username);
        } catch (UserNotFoundException $e) {
            throw new AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);
        }

        if (!Passwords::verify($password, $data['password'])) {
            throw new AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);
        } elseif (!Passwords::needsRehash($data['password'])) {
            $this->userManager->changePassword($username, $password);
        }

        return $this->userManager->getUser($username);
    }
}
