<?php

namespace App\Controllers;

class User
{
    public $login;
    public $password;
    public $dbConnection;
    public $token;

    public function __construct($login, $password, $dbConnection)
    {
        $this->login = $login;
        $this->password = $password;
        $this->dbConnection = $dbConnection;
    }

    public function check(): bool
    {
        $query = $this->dbConnection->prepare("SELECT * FROM users WHERE email = ?");
        $query->execute([$this->login]);
        $user = $query->fetch();
        if ($user && password_verify($this->password, $user['password'])) {
            return true;
        } else {
            return false;
        }
    }

    public function setToken(): string
    {
        $token = Random::getString("20");
        $this->token = $token;
        $query = $this->dbConnection->prepare("UPDATE users SET token = ? WHERE email = ?");
        $query->execute([
            $token,
            $this->login
        ]);
        return $token;
    }

    public static function checkToken($token, $dbConnection): bool
    {
        $query = $dbConnection->prepare("SELECT * FROM users WHERE token = ?");
        $query->execute([$token]);
        $tokens = $query->fetch();
        if ($tokens) {
            return true;
        }
        return false;
    }

}
