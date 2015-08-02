<?php
namespace Demo\IdServicesBundle\Tests\Lib\Classes\SaIdNumberGenerator;

use Demo\IdServicesBundle\Lib\Classes\SaIdNumberGenerator\SaIdNumberGenerator;

class SaIdNumberGeneratorTest extends \PHPUnit_Framework_TestCase
{
    const LOCAL_MALE_ID_NUMBER_REG_EXP = "/^150201[5-9]\d{3}0[8-9]\d$/";
    const FOREIGN_MALE_ID_NUMBER_REG_EXP = "/^150201[5-9]\d{3}1[8-9]\d$/";
    const LOCAL_FEMALE_ID_NUMBER_REG_EXP = "/^150201[0-4]\d{3}0[8-9]\d$/";
    const FOREIGN_FEMALE_ID_NUMBER_REG_EXP = "/^150201[0-4]\d{3}1[8-9]\d$/";
    
    private $utils;
    
    public function setup()
    {
        $this->utils = $this->getMockBuilder('\Demo\IdServicesBundle\Lib\Interfaces\UtilsInterface')
                     ->getMock();
        $this->utils->expects($this->any())->method('generateCheckBit')
             ->will($this->returnValue(1));
    }
    
    public function testCanConstruct()
    {
        $dataOfBirth = "150201";
        $saIdNumberGenerator = new SaIdNumberGenerator($this->utils, $dataOfBirth);
    }
    
    /**
     * @expectedException \Demo\IdServicesBundle\Lib\Exceptions\InvalidFormatException
     */
    public function testCanThrowExceptionOnInvalidDateLengthInput()
    {
        $dataOfBirth = "15020";//Shorter Date
        $saIdNumberGenerator = new SaIdNumberGenerator($this->utils, $dataOfBirth);
    }
    
    /**
     * @expectedException \Demo\IdServicesBundle\Lib\Exceptions\InvalidFormatException
     */
    public function testCanThrowExceptionOnInvalidDateInput()
    {
        $dataOfBirth = "Invalid Date";//Invalid date
        $saIdNumberGenerator = new SaIdNumberGenerator($this->utils, $dataOfBirth);
    }
    
    public function testCanGenerateLocalMaleIdNumber()
    {
        $dataOfBirth = "150201";
        $gender = "male";
        $origin = "local";
        $saIdNumberGenerator = new SaIdNumberGenerator($this->utils, $dataOfBirth);
        $results = $saIdNumberGenerator->generateIdNumber($gender, $origin);
        
        $hasTheCorrectFormat = preg_match(self::LOCAL_MALE_ID_NUMBER_REG_EXP, $results) !== false;
        $this->assertTrue($hasTheCorrectFormat, "The format of the generated ID number is not valid");
    }
    
    public function testCanGenerateForeignMaleIdNumber()
    {
        $dataOfBirth = "150201";
        $gender = "male";
        $origin = "foreign";
        $saIdNumberGenerator = new SaIdNumberGenerator($this->utils, $dataOfBirth);
        $results = $saIdNumberGenerator->generateIdNumber($gender, $origin);
        
        $hasTheCorrectFormat = preg_match(self::FOREIGN_MALE_ID_NUMBER_REG_EXP, $results) !== false;
        $this->assertTrue($hasTheCorrectFormat, "The format of the generated ID number is not valid");
    }
    
    public function testCanGenerateLocalFemaleIdNumber()
    {
        $dataOfBirth = "150201";
        $gender = "female";
        $origin = "local";
        $saIdNumberGenerator = new SaIdNumberGenerator($this->utils, $dataOfBirth);
        $results = $saIdNumberGenerator->generateIdNumber($gender, $origin);
        
        $hasTheCorrectFormat = preg_match(self::LOCAL_FEMALE_ID_NUMBER_REG_EXP, $results) !== false;
        $this->assertTrue($hasTheCorrectFormat, "The format of the generated ID number is not valid");
    }
    
    public function testCanGenerateForeignFemaleIdNumber()
    {
        $dataOfBirth = "150201";
        $gender = "female";
        $origin = "foreign";
        $saIdNumberGenerator = new SaIdNumberGenerator($this->utils, $dataOfBirth);
        $results = $saIdNumberGenerator->generateIdNumber($gender, $origin);
        
        $hasTheCorrectFormat = preg_match(self::FOREIGN_FEMALE_ID_NUMBER_REG_EXP, $results) !== false;
        $this->assertTrue($hasTheCorrectFormat, "The format of the generated ID number is not valid");
    }
    
    /**
     * @expectedException \Demo\IdServicesBundle\Lib\Exceptions\InvalidFormatException
     */
    public function testCanThrowExceptionOnInvalidGender()
    {
        $dataOfBirth = "150201";
        $gender = "Invalid Gender";
        $origin = "foreign";
        $saIdNumberGenerator = new SaIdNumberGenerator($this->utils, $dataOfBirth);
        $results = $saIdNumberGenerator->generateIdNumber($gender, $origin);
    }
    
    /**
     * @expectedException \Demo\IdServicesBundle\Lib\Exceptions\InvalidFormatException
     */
    public function testCanThrowExceptionOnInvalidOrigin()
    {
        $dataOfBirth = "150201";
        $gender = "male";
        $origin = "Invalid origin";
        $saIdNumberGenerator = new SaIdNumberGenerator($this->utils, $dataOfBirth);
        $results = $saIdNumberGenerator->generateIdNumber($gender, $origin);
    }
}