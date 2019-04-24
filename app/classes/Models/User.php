<?php

namespace App\Models;

Class User extends Core\Database\Model {

    public function __construct(\Core\Database\Connection $conn) {
        $this->conn = $conn;
        parent::__construct($this->conn, 'users', [
            'email',
            'password',
            'fullname',
            'age',
            'gender',
            'photo'
        ]);
    }

}
