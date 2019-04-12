<?php

namespace Core\User;

class Session extends \Core\User\Abstracts\Session {

    /**
     * Konstruktorius pradeda sesiją ir bando
     * user'į prijungti su Cookie
     */
    public function __construct(\Core\User\Repository $repo) {
        $this->repo = $repo;
        $this->is_logged_in = false;
        
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->loginViaCookie();
    }

    /**
     * Return'inti user'io objektą
     */
    public function getUser() {
        return $this->user;
    }

    public function isLoggedIn() {
        return $this->is_logged_in;
    }

    /**
     * Bando user'į priloginti per $email ir $password
     * 
     * Jeigu blogas passwordas/email, return'inti
     * LOGIN_ERR_CREDENTIALS
     * 
     * Jeigu useris not active, return'inti
     * LOGIN_ERR_NOT_ACTIVE
     * 
     * Jeigu viskas gerai: 
     * 1# į $_SESSION išsaugoti email ir password
     * 2# nusettinti $this->user
     * 3# return'inti LOGIN_SUCCESS
     * 
     *      * 
     * @param type $email
     * @param type $password
     * @return int
     */
    public function login($email, $password): int {
        $user = $this->repo->load($email);

        if ($user) {
            if ($password === $user->getPassword()) {
                if ($user->getIsActive()) {
                    $_SESSION['email'] = $email;
                    $_SESSION['password'] = $password;
                    $this->user = $user;
                    $this->is_logged_in = true;

                    return self::LOGIN_SUCCESS;
                } else {
                    return self::LOGIN_ERR_NOT_ACTIVE;
                }
            }
        }

        return self::LOGIN_ERR_CREDENTIALS;
    }

    /**
     * Bando user'į priloginti iš 
     * Server-Side'o Cookie $_SESSION
     * bandant jį priloginti su $_SESSION'e
     * išsaugotais email ir password
     */
    public function loginViaCookie() {
        if (isset($_SESSION['email']) && isset($_SESSION['password'])) {
            return $this->login($_SESSION['email'], $_SESSION['password']);
        }
    }

    /**
     * Išvalyti $_SESSION
     * užbaigti sesiją (Google)
     * ištrinti sesijos cookie (Google)
     * nustatyti is_logged_in
     * nustatyti $this->user
     */
    public function logout() {
        $_SESSION = [];
        session_destroy();
        setcookie(session_name(), '', time() - 3600);
        $this->is_logged_in = false;
        $this->user = null;
    }

}
