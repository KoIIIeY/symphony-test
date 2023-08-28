<?php

namespace App\Parser;

class Importer {


    public function __construct(private Filler $filler)
    {
    }

    public function setData(array $data){
        $this->filler->setData($data);
        return $this;
    }

    public function done(){
        $this->filler->done();
        return $this;
    }

    public function setPerPage(int $perPage){
        $this->filler->setPerPage($perPage);
        return $this;
    }



}
