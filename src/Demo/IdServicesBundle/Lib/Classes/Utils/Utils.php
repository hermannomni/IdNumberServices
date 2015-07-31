<?php

namespace Demo\IdServicesBundle\Lib\Classes\Utils;

class Utils
{
    /**
     * Computes the check bit of an ID number
     * @param string $idNumber The ID number that will be used to compute the check bit
     */
    public static function generateCheckBit($idNumber)
    {
        $a = 0;
        for ($i = 0; $i < 6; $i++){
            $a += intval(substr($idNumber, 2 * $i, 1));
        }
        
        $b = 0;
        for ($i = 0; $i < 6; $i++){
            $b = $b * 10 + intval(substr($idNumber, 2 * $i + 1, 1));
        }
        
        $b *= 2;
        $c = 0;
        do {
            $c += $b % 10;
            $b = $b / 10;
        } while($b > 0);
        
        $c += $a;
        $d = 10 - ($c % 10);
        
        if ($d == 10){
            $d = 0;
        }
        
        return $d;
    }
}