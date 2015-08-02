<?php
namespace Demo\IdServicesBundle\Tests\Lib\Classes\SaIdNumberGenerator;

use Demo\FrontEndBundle\Entity\IdNumberServiceClient;

class IdNumberServiceClientTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerateIdNumber()
    {
        $utils = $this->getMockBuilder('\Demo\FrontEndBundle\Lib\Interfaces\UtilsInterface')
                     ->getMock();
        $utils->expects($this->any())->method('httpRequests')
             ->will($this->returnValue(json_encode(array(
                "status"    =>  200,
                "message"   =>  "The ID number has been generated successfully",
                "data"      =>  array("idNumber"    =>  "1508077737097")
             ))));
        $protocol = "http://";
        $host = "dummyhost.com";
        $dateOfBirth = "150807";
        $gender = "male";
        $origin = "local";
        
        $idNumberServiceClient = new IdNumberServiceClient($utils, $protocol, $host);
        $idNumberServiceClient->generateIdNumber($dateOfBirth, $gender, $origin);
    }
    
    public function testCheckIdNumber()
    {
        $utils = $this->getMockBuilder('\Demo\FrontEndBundle\Lib\Interfaces\UtilsInterface')
                     ->getMock();
        $utils->expects($this->any())->method('httpRequests')
             ->will($this->returnValue(json_encode(array(
                "status"    =>  200,
                "message"   =>  "The ID number and the details provided matches",
                "data"      =>  array()
             ))));
        $protocol = "http://";
        $host = "dummyhost.com";
        $dateOfBirth = "150807";
        $gender = "male";
        $origin = "local";
        $idNumber = "1508077737097";
        
        $idNumberServiceClient = new IdNumberServiceClient($utils, $protocol, $host);
        $idNumberServiceClient->generateIdNumber($idNumber, $dateOfBirth, $gender, $origin);
    }
    
    /**
     * @expectedException \Demo\FrontEndBundle\Lib\Exceptions\MalformatedResponseException
     */
    public function testGenerateIdNumberCanThrowExceptionOnInvalidApiReply()
    {
        $utils = $this->getMockBuilder('\Demo\FrontEndBundle\Lib\Interfaces\UtilsInterface')
                     ->getMock();
        $utils->expects($this->any())->method('httpRequests')
             ->will($this->returnValue("Invalid Api Reply"));
        $protocol = "http://";
        $host = "dummyhost.com";
        $dateOfBirth = "150201";
        $gender = "male";
        $origin = "local";
        
        $idNumberServiceClient = new IdNumberServiceClient($utils, $protocol, $host);
        $idNumberServiceClient->generateIdNumber($dateOfBirth, $gender, $origin);
    }
    
    /**
     * @expectedException \Demo\FrontEndBundle\Lib\Exceptions\MalformatedResponseException
     */
    public function testCheckIdNumberCanThrowExceptionOnInvalidApiReply()
    {
        $utils = $this->getMockBuilder('\Demo\FrontEndBundle\Lib\Interfaces\UtilsInterface')
                     ->getMock();
        $utils->expects($this->any())->method('httpRequests')
             ->will($this->returnValue("Invalid Api Reply"));
        $protocol = "http://";
        $host = "dummyhost.com";
        $dateOfBirth = "150807";
        $gender = "male";
        $origin = "local";
        $idNumber = "1508077737097";
        
        $idNumberServiceClient = new IdNumberServiceClient($utils, $protocol, $host);
        $idNumberServiceClient->generateIdNumber($idNumber, $dateOfBirth, $gender, $origin);
    }
}