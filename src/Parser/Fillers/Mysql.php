<?php

namespace App\Parser\Fillers;
use App\Parser\Filler;
use Doctrine\Persistence\ManagerRegistry;

class Mysql extends Filler {

    private $entityManager;
    private $data;

    public function __construct(private ManagerRegistry $doctrine)
    {
        $this->entityManager = $this->doctrine->getManager('mysql');
        $this->entityManager->getConnection()->getConfiguration()->setSQLLogger(null);
        $this->entityManager->getConnection()->getConfiguration()->setMiddlewares([]);
    }

    public function setData(array $row)
    {
        $this->lineNumber++;

        $this->data[] = $row;

        if($this->lineNumber % $this->perPage === 0){
            $this->done();
        }
    }


    public function done()
    {
        $blocks = [];
        foreach ($this->data as $block){
            $blocks []= "('{$block[0]}', '{$block[1]}', '{$block[2]}')";
        }

        if(!count($blocks)){
            $this->lastRow = $this->lineNumber;
            $this->data = [];
            return;
        }

        $sql = 'insert into imported(uuid, ctime, eventname) values '.implode(',', $blocks);
        $this->entityManager->getConnection()->executeUpdate($sql);

        $this->lastRow = $this->lineNumber;
        $this->data = [];

    }


}