<?php

class Auth
{
    public function login($user, $password)
    {
        return password_verify($password, $user->getMdp()) ? $this->authenticate($user) :  false;
    }

    public function authenticate($user, $id = null)
    {
        $_SESSION['auth'] = $id ? : $user->getId();

        return $_SESSION['logged'] = true;
    }

    public function isLogged()
    {
        return isset($_SESSION['logged']);
    }

    public function logged()
    {
        return $_SESSION['logged'];
    }

    public function logout()
    {
        unset($_SESSION['auth']);
        unset($_SESSION['role']);
    }
}