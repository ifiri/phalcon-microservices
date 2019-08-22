<?php

namespace App\Models;

use Phalcon\Mvc\Model;

/**
 * Represents a singular User.
 */
class User extends Model
{
    protected $id;
    protected $login;
    protected $password;
    protected $name;
    protected $created_at;

    /**
     * Sets source table for this model
     * 
     * @return void
     */
    public function initialize()
    {
        $this->setSource('users');
    }

    /**
     * Returns User ID
     * 
     * @return int
     */
    public function getId(): int
    {
        return (int)$this->id;
    }

    /**
     * Returns User login
     * 
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * Returns current User password hash
     * 
     * @return string
     */
    public function getPasswordHash(): string
    {
        return $this->password;
    }

    /**
     * Returns current User name
     * 
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns date when User was created
     * 
     * @return int
     */
    public function getCreatedAt(): int
    {
        return $this->created_at;
    }

    /**
     * Rewrite user login with provided one
     * 
     * @param string $login 
     * @return void
     */
    public function setLogin(string $login)
    {
        $this->login = $login;
    }

    /**
     * Rewrite user password with provided one.
     * Uses Password API for hashing purposes
     * 
     * @param string $password
     * @return void
     */
    public function setPassword(string $password)
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Rewrite user name with provided one
     * 
     * @param string $name 
     * @return void
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Checks if provided password match with current user password
     * 
     * @param string $password 
     * @return bool
     */
    public function isPasswordCorrect(string $password): bool
    {
        return password_verify($password, $this->getPasswordHash());
    }
}