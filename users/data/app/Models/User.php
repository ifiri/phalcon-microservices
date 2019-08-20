<?php

namespace App\Models;

use Phalcon\Mvc\Model;

class User extends Model
{
    protected $id;
    protected $login;
    protected $password;
    protected $name;
    protected $created_at;

    public function initialize()
    {
        $this->setSource('users');
    }

    public function getId(): int
    {
        return (int)$this->id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPasswordHash(): string
    {
        return $this->password;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setLogin(string $login)
    {
        $this->login = $login;
    }

    public function setPassword(string $password)
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function isPasswordCorrect(string $password): bool
    {
        return password_verify($password, $this->getPasswordHash());
    }
}