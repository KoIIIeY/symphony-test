<?php

namespace App\Parser;

use App\Entity\Imported;

abstract class Filler {

    public int $perPage = 50;
    public int $lineNumber = 0;

    abstract public function setData(array $data);
    public function setPerPage(int $perPage){
        $this->perPage = $perPage;
    }
    abstract public function done();

}
