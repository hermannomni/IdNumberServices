<?php

namespace Demo\FrontEndBundle\Entity;

use Demo\FrontEndBundle\Lib\Interfaces\UtilsInterface;
use Demo\FrontEndBundle\Lib\Exceptions\MalformatedResponseException;

class IdNumberServiceClient
{
    const GENERATE_ID_NUMBER_ACTION = "api/generateIdNumber";
    const CHECK_ID_NUMBER_ACTION = "api/checkIdNumber";
    private $utils;
    private $hostUrl;
    
    public function __construct(UtilsInterface $utils, $protocol, $host)
    {
        $this->utils = $utils;
        $this->hostUrl = sprintf("%s%s", $protocol, $host);
    }
    
    public function generateIdNumber($dateOfBirth, $gender, $origin)
    {
        $requestUrl = sprintf("%s/%s/%s/%s/%s", $this->hostUrl, self::GENERATE_ID_NUMBER_ACTION,
                                urlencode($dateOfBirth), urlencode($gender), urlencode($origin));
        $rawResponse = $this->utils->httpRequests($requestUrl, array(), "GET");
        
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
        $rawResponse = $this->utils->httpRequests($requestUrl, $params);
        
        return $this->decodeServerResponse($rawResponse);
    }
    
    private function decodeServerResponse($rawResponse)
    {
        $response = json_decode($rawResponse, true);
        if (!is_array($response) || !isset($response["status"]) || !isset($response["message"])
                || !isset($response["data"])){
            throw new MalformatedResponseException("An error occured while decoding the data from the server",
                        array(
                            "rawResponse"   =>  $rawResponse
                        )
            );
        }
        
        return $response;
    }
}