<?php

namespace App\Rap;

class Song extends Abstracts\Song {

    public function getSong(): array {
        return $this->loadAll();
    }

}
