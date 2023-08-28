<?php

namespace App\Parser;

use App\Entity\Imported;

abstract class Filler {

    public int $per_page = 50;
    public $lastRow;
    public int $lineNumber = 0;
    public bool $doneImport = false;

    abstract public function setData(array $data);
    public function setPerPage(int $per_page){
        $this->per_page = $per_page;
    }
    abstract public function done();

}
