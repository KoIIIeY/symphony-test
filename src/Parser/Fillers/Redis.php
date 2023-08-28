<?php

namespace App\Parser\Fillers;

use App\Parser\Filler;
use SymfonyBundles\RedisBundle\Redis\ClientInterface;

class Redis extends Filler {
    
    
    public function __construct(private ClientInterface $redis)
    {
    }

    public function setData(array $data)
    {
        $this->redis->set($data[2], json_encode($data));
    }

    public function done()
    {

    }
}