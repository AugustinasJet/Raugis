<?php

namespace Core\Database;

use PDO;

class Connection extends \Core\Database\Abstracts\Connection {

    public function __construct($creds) {
        $this->pdo = null;
        $this->setCredentials($creds);
    }

    public function connect() {
        if (!$this->pdo) {
            try {
                $this->pdo = new PDO(
                        "mysql:host=$this->host", $this->user, $this->pass
                );
                
                if (DEBUG) {
                    $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
                }
            } catch (Exception $ex) {
                throw new \PDOException('Could not connect to database!');
            }
        }
    }

}
