<?php

namespace Demo\FrontEndBundle\Lib\Classes\Utils;

use Demo\FrontEndBundle\Lib\Interfaces\UtilsInterface;

class Utils implements UtilsInterface
{
    public function httpRequests($requestUrl, $params = array(), $method = "post")
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $requestUrl);
        if (strtolower($method) === "post"){
            curl_setopt($ch, CURLOPT_POST, 1);
            if (count($params) > 0){
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
            }
        }
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        
        return $output;
    }
}