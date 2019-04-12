<?php

namespace Core\User;

class Repository extends \Core\User\Abstracts\Repository {

    /**
     * Patikrinam are user'is su tokiu email'u egzistuoja
     * Jeigu ne, tada įtraukiam jį į duombazę ir
     * returniname REGISTER_SUCCESS
     * Jeigu egzistuoja, returniname REGISTER_ERR_EXISTS
     */
    public function register(\Core\User\User $user) {
        if (!$this->exists($user)) {
            $this->insert($user);
            return self::REGISTER_SUCCESS;
        }
        return self::REGISTER_ERR_EXISTS;
    }

}
