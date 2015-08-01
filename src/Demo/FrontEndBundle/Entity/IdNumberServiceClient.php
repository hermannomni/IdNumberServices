<?php

namespace Demo\FrontEndBundle\Entity;

use Demo\FrontEndBundle\Lib\Classes\Utils\Utils;
use Demo\FrontEndBundle\Lib\Exceptions\MalformatedResponseException;

class SaIdNumberServiceClient
{
    const GENERATE_ID_NUMBER_ACTION = "api/generateIdNumber";
    const CHECK_ID_NUMBER_ACTION = "api/checkIdNumber";
    private $hostUrl;
    
    public function __construct($protocol, $host)
    {
        $this->hostUrl = sprintf("%s%s", $protocol, $host);
    }
    
    public function generateIdNumber($dateOfBirth, $gender, $origin)
    {
        $requestUrl = sprintf("%s/%s/%s/%s/%s", $this->hostUrl, self::GENERATE_ID_NUMBER_ACTION,
                                urlencode($dateOfBirth), urlencode($gender), urlencode($origin));
        $rawResponse = Utils::httpRequests($requestUrl);
        
        return $this->decodeServerResponse($rawResponse);
    }
    
    public function checkIdNumber($idNumber, $dateOfBirth, $gender, $origin)
    {
        $params = array(
            "idNumber"      =>  $idNumber,
            "dateOfBirth"   =>  $dateOfBirth,
            "gender"        =>  $gender,
            "origin"        =>  $origin
        );
        $requestUrl = sprintf("%s/%s", $this->hostUrl, self::CHECK_ID_NUMBER_ACTION);
        $rawResponse = Utils::httpRequests($requestUrl, $params);
        
        return $this->decodeServerResponse($rawResponse);
    }
    
    private function decodeServerResponse($rawResponse)
    {
        $response = json_decode($rawResponse, true);
        if (!is_array($response) || !isset($response["status"]) || $response["message"]
                || $response["data"]){
            throw new MalformatedResponseException("An error occured while decoding the data from the server",
                        array(
                            "rawResponse"   =>  $rawResponse
                        ));
        }
        
        return $response;
    }
}