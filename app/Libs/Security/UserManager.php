<?php declare(strict_types=1);

namespace App\Libs\Security;

use App\Libs\Config;
use Nette\Neon\Neon;
use Nette\Security\Passwords;
use Nette\Utils\FileSystem;

final class UserManager
{
    /**
     * @var Config
     */
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $username
     *
     * @return array
     * @throws UserNotFoundException
     */
    public function getData($username)
    {
        $file = $this->config->getUserFilePath($username);
        if (!is_file($file)) {
            throw new UserNotFoundException("User `$username` does not exist.");
        }

        return Neon::decode(FileSystem::read($file));
    }

    /**
     * @param string $username
     *
     * @return User
     */
    public function getUser($username)
    {
        $data = $this->getData($username);

        return new User($data['username'], $data['name'], $data['roles']);
    }

    /**
     * @param string $username
     * @param string $name
     * @param string $password
     *
     * @return User
     * @throws UserExistsException
     */
    public function create($username, $name, $password)
    {
        $roles = ['admin'];

        $file = $this->config->getUserFilePath($username);
        if (is_file($file)) {
            throw new UserExistsException("User `$username` already exists.");
        }

        $data = [
            'username' => $username,
            'name' => $name,
            'password' => Passwords::hash($password),
            'roles' => $roles,
        ];
        FileSystem::write($file, Neon::encode($data));

        return new User($username, $name, $roles);
    }

    /**
     * @param string $username
     * @param string $password
     */
    public function changePassword($username, $password)
    {
        $data = $this->getData($username);
        $data['password'] = Passwords::hash($password);

        FileSystem::write($this->config->getUserFilePath($username), Neon::encode($data));
    }
}

final class UserExistsException extends \Exception
{
}

final class UserNotFoundException extends \Exception
{
}
