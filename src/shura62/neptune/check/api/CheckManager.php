<?php

declare(strict_types=1);

namespace shura62\neptune\check\api;

use shura62\neptune\check\impl\movement\fastladder\FastLadderA;
use shura62\neptune\check\impl\movement\fly\FlyA;
use shura62\neptune\check\impl\movement\fly\FlyB;
use shura62\neptune\check\impl\movement\fly\FlyC;
use shura62\neptune\check\impl\movement\fly\FlyD;
use shura62\neptune\check\impl\movement\motion\MotionA;
use shura62\neptune\check\impl\movement\motion\MotionB;
use shura62\neptune\check\impl\movement\motion\MotionC;
use shura62\neptune\check\impl\movement\noslowdown\NoSlowdownA;
use shura62\neptune\check\impl\movement\phase\PhaseA;
use shura62\neptune\check\impl\movement\speed\SpeedA;
use shura62\neptune\check\impl\movement\speed\SpeedB;
use shura62\neptune\check\impl\movement\speed\SpeedC;
use shura62\neptune\check\impl\movement\speed\SpeedD;
use shura62\neptune\check\impl\movement\speed\SpeedE;
use shura62\neptune\check\impl\other\horion\HorionA;
use shura62\neptune\check\impl\packet\badpackets\BadPacketsA;
use shura62\neptune\check\impl\packet\badpackets\BadPacketsB;
use shura62\neptune\check\impl\packet\badpackets\BadPacketsC;
use shura62\neptune\check\impl\packet\nofall\NoFallA;

class CheckManager {

    private $checks;
    
    public function __construct() {
        $this->checks = [
            new SpeedA(),
            new SpeedB(),
            new SpeedC(),
            new SpeedD(),
            new SpeedE(),
            new FlyA(),
            new FlyB(),
            new FlyC(),
            new FlyD(),
            new MotionA(),
            new MotionB(),
            new MotionC(),
            new FastLadderA(),
            new NoSlowdownA(),
            new NoFallA(),
            new BadPacketsA(),
            new BadPacketsB(),
            new BadPacketsC(),
            new PhaseA(),
            new HorionA(),
        ];
    }
    
    public function loadChecks() : array{
        return $this->checks;
    }
    
}