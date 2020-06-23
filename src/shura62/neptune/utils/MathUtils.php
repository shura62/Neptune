<?php

declare(strict_types=1);

namespace shura62\neptune\utils;

use pocketmine\math\Vector3;

class MathUtils {

    public static function gcd(int $a, int $b) : float{
        return (float) gmp_strval(gmp_gcd((string) $a, (string) $b));
    }
    
    public static function getDirectionBetweenLocations(Vector3 $a, Vector3 $b) : float{
        $difX = $b->getX() - $a->getX();
        $difZ = $b->getZ() - $a->getZ();
        return ((atan2($difZ, $difX) * 180 / pi()) - 90);
    }
    
    public static function getDistanceBetweenAngles(float $a, float $b) : float{
        $dist = abs($a % 360 - $b % 360);
        return abs(min(360 - $dist, $dist));
    }
    
    public static function IEEERemainder(float $dividend, float $divisor) : float{
        return $dividend - ($divisor * round($dividend / $divisor));
    }
    
    public static function lcd(float $a, float $b) : float{
        return $a * ($b / self::absGCD($a, $b));
    }
    
    public static function absGCD(float $a, float $b) : float{
        try {
            while ($b > 0) {
                $temp = $b;
                $b = $a % $b;
                $a = $temp;
            }
            return $a;
        } catch (\DivisionByZeroError $e) {
            return 0;
        }
    }
    
    public static function getStandardDeviation(array $nums) : float{
        $deviation = 0;
        $mean = array_sum($nums) / count($nums);
        foreach ($nums as $num)
            $deviation += pow($num - $mean, 2);
        
        return sqrt($deviation / count($nums));
    }
    
}