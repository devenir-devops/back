<?php

namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;

final class User implements JWTUserInterface
{
    /**
     * @var string $username
     */
    public string $username;
    /**
     * @var array<string> $roles
     */
    public array $roles;
    /**
     * @var string $email
     */
    public string $email;

    /**
     * @param string $username
     * @param array<string> $roles
     * @param string $email
     */
    public function __construct(string $username, array $roles, string $email)
    {
        $this->username = $username;
        $this->roles = $roles;
        $this->email = $email;
    }

    /**
     * @param string $username
     * @param array<string> $payload
     * @return User
     */
    public static function createFromPayload($username, array $payload): User
    {
        return new self(
            $payload['sub'],
            ["ROLE_USER"], // Added by default
            $username  // Custom
        );
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

}