<?php
namespace Demo\IdServicesBundle\Tests\Lib\Classes\SaIdNumberValidator;

use Demo\IdServicesBundle\Lib\Classes\SaIdNumberValidator\SaIdNumberValidator;

class SaIdNumberValidatorTest extends \PHPUnit_Framework_TestCase
{
    private $utils;
    
    public function setup()
    {
        $this->utils = $this->getMockBuilder('\Demo\IdServicesBundle\Lib\Interfaces\UtilsInterface')
                     ->getMock();
        $this->utils->expects($this->any())->method('generateCheckBit')
             ->will($this->returnValue(7));
    }
    
    public function testCanConstruct()
    {
        $saIdNumberValidator = new SaIdNumberValidator($this->utils, "8002222011197");
    }
    
    /**
     * @expectedException \Demo\IdServicesBundle\Lib\Exceptions\InvalidFormatException
     */
    public function testCanThrowExceptionOnShortIdNumber()
    {
        $saIdNumberValidator = new SaIdNumberValidator($this->utils, "800222201159");
    }
    
    /**
     * @expectedException \Demo\IdServicesBundle\Lib\Exceptions\InvalidFormatException
     */
    public function testCanThrowExceptionOnLongIdNumber()
    {
        $saIdNumberValidator = new SaIdNumberValidator($this->utils, "8002222011597111");
    }
    
    /**
     * @expectedException \Demo\IdServicesBundle\Lib\Exceptions\InvalidFormatException
     */
    public function testCanThrowExceptionOnInvalidCharactersInIdNumber()
    {
        $saIdNumberValidator = new SaIdNumberValidator($this->utils, "Invalid Character In Id Number");
    }
    
    /**
     * @expectedException \Demo\IdServicesBundle\Lib\Exceptions\InvalidFormatException
     */
    public function testCanThrowExceptionOnInvalidOriginValueInIdNumber()
    {
        $saIdNumberValidator = new SaIdNumberValidator($this->utils, "8002222011597");//8002222011<this digit should be 0 or 1>97
    }
    
    /**
     * @expectedException \Demo\IdServicesBundle\Lib\Exceptions\InvalidFormatException
     */
    public function testCanThrowExceptionOnInvalidRacialIdentifierValueInIdNumber()
    {
        $saIdNumberValidator = new SaIdNumberValidator($this->utils, "8002222011117");//80022220111<this digit should be 8 or 9>7
    }
    
    /**
     * @expectedException \Demo\IdServicesBundle\Lib\Exceptions\InvalidFormatException
     */
    public function testCanThrowExceptionOnInvalidCheckBitValueInIdNumber()
    {
        $saIdNumberValidator = new SaIdNumberValidator($this->utils, "8002222011595");
    }
    
    public function testCanCheckLocalMaleIdNumber()
    {
        $dateOfBirth = "150807";
        $gender = "male";
        $origin = "local";
        $saIdNumberValidator = new SaIdNumberValidator($this->utils, "1508077737097");
        $saIdNumberValidator->checkIdNumber($dateOfBirth, $gender, $origin);
    }
    
    public function testCanCheckForeignMaleIdNumber()
    {
        $dateOfBirth = "150807";
        $gender = "male";
        $origin = "foreign";
        $saIdNumberValidator = new SaIdNumberValidator($this->utils, "1508076431197");
        $saIdNumberValidator->checkIdNumber($dateOfBirth, $gender, $origin);
    }
    
    public function testCanCheckLocalFemaleIdNumber()
    {
        $dateOfBirth = "150807";
        $gender = "female";
        $origin = "local";
        $saIdNumberValidator = new SaIdNumberValidator($this->utils, "1508070529087");
        $saIdNumberValidator->checkIdNumber($dateOfBirth, $gender, $origin);
    }
    
    public function testCanCheckForeignFemaleIdNumber()
    {
        $dateOfBirth = "150807";
        $gender = "female";
        $origin = "foreign";
        $saIdNumberValidator = new SaIdNumberValidator($this->utils, "1508070622197");
        $saIdNumberValidator->checkIdNumber($dateOfBirth, $gender, $origin);
    }
    
    /**
     * @expectedException \Demo\IdServicesBundle\Lib\Exceptions\IdValidationException
     */
    public function testCanThrowExceptionOnInvalidDateInput()
    {
        $dateOfBirth = "Invalid date of birth";
        $gender = "female";
        $origin = "foreign";
        $saIdNumberValidator = new SaIdNumberValidator($this->utils, "1508070622197");
        $saIdNumberValidator->checkIdNumber($dateOfBirth, $gender, $origin);
    }
    
    /**
     * @expectedException \Demo\IdServicesBundle\Lib\Exceptions\InvalidFormatException
     */
    public function testCanThrowExceptionOnInvalidGenderInput()
    {
        $dateOfBirth = "150807";
        $gender = "Invalid gender";
        $origin = "foreign";
        $saIdNumberValidator = new SaIdNumberValidator($this->utils, "1508070622197");
        $saIdNumberValidator->checkIdNumber($dateOfBirth, $gender, $origin);
    }
    
    /**
     * @expectedException \Demo\IdServicesBundle\Lib\Exceptions\InvalidFormatException
     */
    public function testCanThrowExceptionOnInvalidOriginInput()
    {
        $dateOfBirth = "150807";
        $gender = "female";
        $origin = "Invalid origin";
        $saIdNumberValidator = new SaIdNumberValidator($this->utils, "1508070622197");
        $saIdNumberValidator->checkIdNumber($dateOfBirth, $gender, $origin);
    }
    
    /**
     * @expectedException \Demo\IdServicesBundle\Lib\Exceptions\IdValidationException
     */
    public function testCanThrowExceptionOnWrongGenderDetailsSupplied()
    {
        $dateOfBirth = "150807";
        $gender = "male";
        $origin = "foreign";
        $saIdNumberValidator = new SaIdNumberValidator($this->utils, "1508070622197");
        $saIdNumberValidator->checkIdNumber($dateOfBirth, $gender, $origin);
    }
    
    /**
     * @expectedException \Demo\IdServicesBundle\Lib\Exceptions\IdValidationException
     */
    public function testCanThrowExceptionOnWrongOriginSupplied()
    {
        $dateOfBirth = "150807";
        $gender = "female";
        $origin = "local";
        $saIdNumberValidator = new SaIdNumberValidator($this->utils, "1508070622197");
        $saIdNumberValidator->checkIdNumber($dateOfBirth, $gender, $origin);
    }
}