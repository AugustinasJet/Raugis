<?php

namespace App\Rap\Abstracts;

abstract class Song extends \App\Rap\ModelLine {

    abstract public function getSong(): array;
}
