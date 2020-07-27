<?php

declare(strict_types=1);

namespace shura62\neptune\check;

use shura62\neptune\check\combat\angle\AngleA;
use shura62\neptune\check\combat\aura\AuraA;
use shura62\neptune\check\combat\aura\AuraB;
use shura62\neptune\check\combat\autoclicker\AutoclickerA;
use shura62\neptune\check\combat\autoclicker\AutoclickerB;
use shura62\neptune\check\combat\autoclicker\AutoclickerC;
use shura62\neptune\check\combat\autoclicker\AutoclickerD;
use shura62\neptune\check\combat\autoclicker\AutoclickerE;
use shura62\neptune\check\combat\autoclicker\AutoclickerF;
use shura62\neptune\check\combat\autoclicker\AutoclickerG;
use shura62\neptune\check\combat\range\RangeA;
use shura62\neptune\check\combat\noswing\NoSwingA;
use shura62\neptune\check\combat\rotation\RotationA;
use shura62\neptune\check\combat\velocity\VelocityA;
use shura62\neptune\check\combat\criticals\CriticalsA;
use shura62\neptune\check\movement\fastladder\FastLadderA;
use shura62\neptune\check\movement\fly\FlyA;
use shura62\neptune\check\movement\fly\FlyB;
use shura62\neptune\check\movement\fly\FlyC;
use shura62\neptune\check\movement\fly\FlyD;
use shura62\neptune\check\movement\fly\FlyE;
use shura62\neptune\check\movement\invalid\InvalidA;
use shura62\neptune\check\movement\invalid\InvalidB;
use shura62\neptune\check\movement\invalid\InvalidC;
use shura62\neptune\check\movement\jesus\JesusA;
use shura62\neptune\check\movement\motion\MotionA;
use shura62\neptune\check\movement\motion\MotionB;
use shura62\neptune\check\movement\noslow\NoSlowA;
use shura62\neptune\check\movement\noslow\NoSlowB;
use shura62\neptune\check\movement\noslow\NoSlowC;
use shura62\neptune\check\movement\scaffold\ScaffoldA;
use shura62\neptune\check\movement\scaffold\ScaffoldB;
use shura62\neptune\check\movement\speed\SpeedA;
use shura62\neptune\check\movement\speed\SpeedB;
use shura62\neptune\check\movement\speed\SpeedC;
use shura62\neptune\check\movement\speed\SpeedD;
use shura62\neptune\check\movement\speed\SpeedE;
use shura62\neptune\check\movement\step\StepA;
use shura62\neptune\check\player\spoof\SpoofA;
use shura62\neptune\check\player\invmove\InvMoveA;
use shura62\neptune\check\player\nofall\NoFallA;
use shura62\neptune\check\player\timer\TimerA;

class CheckManager {

    /** @var Check[] */
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
            new FlyE(),
            new InvalidA(),
            new InvalidB(),
            new InvalidC(),
            new FastLadderA(),
            new MotionA(),
            new MotionB(),
            new NoSlowA(),
            new NoSlowB(),
            new NoSlowC(),
            new JesusA(),
            new ScaffoldA(),
            new ScaffoldB(),
            new StepA(),
            new TimerA(),
            new InvMoveA(),
            new NoFallA(),
            new SpoofA(),
            new AngleA(),
            new AuraA(),
            new AuraB(),
            new AutoclickerA(),
            //new AutoclickerB(),
            //new AutoclickerC(),
            //new AutoclickerD(),
            //new AutoclickerE(),
            //new AutoclickerF(),
            //new AutoclickerG(),
            new NoSwingA(),
            new RotationA(),
            new VelocityA(),
            new RangeA(),
            new CriticalsA(),
        ];
    }

    public function get() : array{
        return $this->checks;
    }

}