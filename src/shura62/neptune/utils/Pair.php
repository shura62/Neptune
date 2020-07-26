<?php

declare(strict_types=1);

namespace shura62\neptune\utils;

class Pair {
    
    private $x, $y;
    
    public function __construct($x, $y) {
        $this->x = $x;
        $this->y = $y;
    }
    
    public function getX() {
        return $this->x;
    }
    
    public function getY() {
        return $this->y;
    }
    
}