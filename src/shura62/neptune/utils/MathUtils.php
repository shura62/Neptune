<?php

declare(strict_types=1);

namespace shura62\neptune\utils;

use pocketmine\math\Vector3;

class MathUtils {

    public static function gcd(int $a, int $b) : float{
        return (float) gmp_strval(gmp_gcd((string) $a, (string) $b));
    }
    
    public static function getDirection(float $yaw, float $pitch) : Vector3{
        $v = new Vector3();
        $rotX = deg2rad($yaw);
        $rotY = deg2rad($pitch);
        
        $v->y = -sin($rotY);
        $xz = cos($rotY);
        $v->x = -$xz * sin($rotX);
        $v->z = $xz * cos($rotX);
        
        return $v;
    }

    public static function angle(Vector3 $a, Vector3 $b) : float{
        $dot = min(max($a->dot($b) / ($a->length() * $b->length()), -1), 1);
        return acos($dot);
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
        return sqrt(self::getVariance($nums));
    }
    
    /**
     * @param - The collection of numbers you want analyze
     * @return - A pair of the high and low outliers
     *
     * @see - https://en.wikipedia.org/wiki/Outlier
     */
    public static function getOutliers(array $collection) : Pair{
        $q1 = self::getMedian(array_splice($collection, 0, (int) ceil(count($collection) / 2)));
        $q3 = self::getMedian(array_splice($collection, (int) ceil(count($collection) / 2), count($collection)));
        
        $iqr = abs($q1 - $q3);
        $lowThreshold = $q1 - 1.5 * $iqr;
        $highThreshold = $q3 + 1.5 * $iqr;
        
        $x = [];
        $y = [];
        
        foreach($collection as $value) {
            if ($value < $lowThreshold) {
                $x[] = $value;
            } elseif ($value > $highThreshold) {
                $y[] = $value;
            }
        }
        
        return new Pair($x, $y);
    }
    
    /**
     * @param $data - The data you want the median from
     * @return - The middle number of that data
     *
     * @see - https://en.wikipedia.org/wiki/Median
     */
    public static function getMedian(array $data) : float{
        if (count($data) % 2 == 0) {
            return ($data[count($data) / 2] + $data[count($data) / 2 - 1]) / 2;
        } else {
            return $data[count($data) / 2];
        }
    }
    
    /**
     * @param $data - The set of numbers / data you want to find the skewness from
     * @return - The skewness running the standard skewness formula.
     *
     * @See - https://en.wikipedia.org/wiki/Skewness
     */
    public static function getSkewness(array $data) : float{
        $sum = array_sum($data);
        $count = count($data);
        
        $numbers = $data;
        sort($numbers);
        
        $mean = $sum / $count;
        $median = ($count % 2 !== 0) ? $numbers[$count / 2] : ($numbers[($count - 1) / 2] + $numbers[$count / 2]) / 2;
        $variance = self::getVariance($data);
        
        return 3 * ($mean - $median) / $variance;
    }
    
    /**
     * @param $data - The set of data you want to find the variance from
     * @return - The variance of the numbers.
     *
     * @See - https://en.wikipedia.org/wiki/Variance
     */
    public static function getVariance(array $data) : float{
        $variance = 0;
        $mean = array_sum($data) / count($data);
        
        foreach ($data as $number) {
            $variance += pow($number - $mean, 2);
        }
        
        return $variance / count($data);
    }
    
    /**
     * @param $data - The set of numbers/data you want to get the kurtosis from
     * @return - The kurtosis using the standard kurtosis formula
     *
     * @See - https://en.wikipedia.org/wiki/Kurtosis
     */
    public static function getKurtosis(array $data) : float{
        $sum = array_sum($data);
        $count = count($data);
        
        if ($count < 3) {
            return 0;
        }
        
        $efficiencyFirst = $count * ($count + 1) / (($count - 1) * ($count - 2) * ($count - 3));
        $efficiencySecond = 3 * pow($count - 1, 2) / (($count - 2) * ($count - 3));
        $average = $sum / $count;
        
        $variance = 0;
        $varianceSquared = 0;
        
        foreach ($data as $number) {
            $variance += pow($average - $number, 2);
            $varianceSquared += pow($average - $number, 4);
        }
        
        if ($variance == 0) {
            return 0;
        }
        
        return $efficiencyFirst * ($varianceSquared / pow($variance / $sum, 2)) - $efficiencySecond;
    }
    
}