<?php declare(strict_types=1);

namespace App\Libs\Security;

use Nette\Security\IIdentity;

final class User implements IIdentity
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $roles = [];

    /**
     * @param string $username
     * @param string $name
     */
    public function __construct($username, $name, array $roles)
    {
        $this->username = $username;
        $this->name = $name;
        $this->roles = $roles;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }
}
