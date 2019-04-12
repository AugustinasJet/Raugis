<?php

namespace App\Rap;

class Line {

    protected $data;

    public function __construct($data = null) {
        if (!$data) {
            $this->data = [
                'email' => null,
                'line' => null,
            ];
        } else {
            $this->setData($data);
        }
    }

    public function setEmail(string $email) {
        $this->data['email'] = $email;
    }

    public function getEmail() {
        return $this->data['email'];
    }

    public function setLine(string $line) {
        $this->data['line'] = $line;
    }

    public function getLine() {
        return $this->data['line'];
    }

    public function setData(array $data) {
        $this->setEmail($data['email'] ?? '');
        $this->setLine($data['line'] ?? '');
    }

    public function getData() {
        return $this->data;
    }

}
