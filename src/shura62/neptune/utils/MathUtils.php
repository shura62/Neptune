<?php

declare(strict_types=1);

namespace shura62\neptune\utils;

use pocketmine\math\Vector3;

class MathUtils {

    public static function gcd(int $a, int $b) : float{
        return (float) gmp_strval(gmp_gcd((string) $a, (string) $b));
    }

    public static function getDirection(float $yaw, float $pitch) : Vector3{
        $y = -sin(deg2rad($pitch));
        $xz = cos(deg2rad($pitch));
        $x = -$xz * sin(deg2rad($yaw));
        $z = $xz * cos(deg2rad($yaw));
        return new Vector3($x, $y, $z);
    }

    public static function angle(Vector3 $a, Vector3 $b) : float{
        try {
            $dot = min(max($a->dot($b) / ($a->length() * $b->length()), -1), 1);
            return acos($dot);
        } catch (\Exception $e) {
            return 0;
        }
    }

    public static function getDirectionFromVectors(Vector3 $a, Vector3 $b) : float{
        $difX = $a->getX() - $b->getX();
        $difZ = $a->getZ() - $b->getZ();

        return (atan2($difZ, $difX) * 180 / M_PI) - 90;
    }

    public static function getDistanceBetweenAngles(float $a, float $b) : float{
        $dist = abs($a % 360 - $b % 360);
        $dist = min(360 - abs($dist), $dist);
        return abs($dist);
    }

    public static function getStandardDeviation(array $nums) : float{
        $deviation = 0;
        $mean = array_sum($nums) / count($nums);
        foreach ($nums as $num) {
            $deviation += pow($num - $mean, 2);
        }

        return sqrt($deviation / count($nums));
    }

}