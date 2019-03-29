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
     * @throws UserNotFoundException
     */
    public function getData(string $username): array
    {
        $file = $this->config->getUserFilePath($username);
        if (!is_file($file)) {
            throw new UserNotFoundException("User `$username` does not exist.");
        }

        return Neon::decode(FileSystem::read($file));
    }

    /**
     * @throws UserNotFoundException
     */
    public function getUser(string $username): User
    {
        $data = $this->getData($username);

        return new User($data['username'], $data['name'], $data['roles']);
    }

    /**
     * @throws UserExistsException
     */
    public function create(string $username, string $name, string $password): User
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

    public function changePassword(string $username, string $password): void
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
