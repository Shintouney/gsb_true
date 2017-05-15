<?php

class Auth
{
    private static $_instance;

    private function __construct()
    {
        // private for singleton pattern
    }

    public static function getInstance()
    {
        if (is_null(self::$_instance))
            self::$_instance = new Auth();

        return self::$_instance;
    }

    public function login($user, $password)
    {
        return password_verify($password, $user->getMdp()) ? $this->authenticate($user) :  false;
    }

    public function authenticate($user)
    {
        $_SESSION = array();
        session_regenerate_id();
        //$_SESSION['auth'] = $id ? : $user->getId();
        //$_SESSION['role'] = false === $user->getRole() ? 'ROLE_USER' : $user->getRole();
        $_SESSION['user'] = serialize($user);

        return $_SESSION['logged'] = true;
    }

    public function isLogged()
    {
        return isset($_SESSION['logged']) && $_SESSION['logged'];
    }

    public function logout()
    {
        session_destroy();
        header('Location: ' . $_SERVER['HTTP_ORIGIN'].$_SERVER['SCRIPT_NAME'].'?page=login');
    }

    public function isGranted($role = 'ROLE_USER', $strict = false)
    {
        $user        = $this->getUser();
        $currentRole = $user->getRole()->getNom();
        $isGranted   = $strict ? $currentRole === $role : $currentRole === $role || $currentRole === 'ROLE_ADMIN';

        return ($this->isLogged() && $isGranted);
    }

   public function getUser()
   {
       if (isset($_SESSION['user'])) {
           return unserialize($_SESSION['user']);
       }

       return false;
   }
}