<?php

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
    private $roles;


    public function __construct(string $username, string $name, array $roles)
    {
        $this->username = $username;
        $this->name = $name;
        $this->roles = $roles;
    }

    public function getId(): string
    {
        return $this->username;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }
}
