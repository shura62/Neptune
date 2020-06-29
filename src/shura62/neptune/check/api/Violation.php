<?php

declare(strict_types=1);

namespace shura62\neptune\check\api;

class Violation {

    private $timeStamp;
    private $information;
    
    public function __construct(string $information) {
        $this->timeStamp = microtime(true);
        $this->information = $information;
    }

    public function getTimeStamp() : float{
        return $this->timeStamp;
    }
    
    public function getInformation() : string{
        return $this->information;
    }
    
}