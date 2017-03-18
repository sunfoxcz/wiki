<?php

namespace App\Libs\Security;

use Nette\DI\Container;
use Nette\Neon\Neon;
use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\IIdentity;
use Nette\Security\Passwords;
use Nette\Utils\FileSystem;


final class Authenticator implements IAuthenticator
{
    /**
     * @var string
     */
    private $userConfigFile;


    public function __construct(Container $container)
    {
        $this->userConfigFile = $container->expand('%appDir%/Config/users.neon');
    }

    /**
     * Performs an authentication against e.g. database.
     * and returns IIdentity on success or throws AuthenticationException
     * @return IIdentity
     * @throws AuthenticationException
     */
    function authenticate(array $credentials)
    {
        list($username, $password) = $credentials;

        if (!is_file($this->userConfigFile)) {
            throw new AuthenticationException('Users config does not exist.');
        }

        $userConfig = Neon::decode(FileSystem::read($this->userConfigFile));
        $user = &$userConfig['users'][$username];

        if (!isset($user)) {
            throw new AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);
        } elseif (!Passwords::verify($password, $user['password'])) {
            throw new AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);
        } elseif (Passwords::needsRehash($user['password'])) {
            // TODO: Neon:encode() returns JSON instead of NEON
            // $user['password'] = Passwords::hash($password);
            // $neon = Neon::encode($userConfig);
            // FileSystem::write($this->userConfigFile, $neon);
        }

        return new User($username, $user['name'], $user['roles']);
    }
}
