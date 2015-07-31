<?php

namespace Demo\IdServicesBundle\Lib\Classes\SaIdNumberGenerator;

use Demo\IdServicesBundle\Lib\Interfaces\IdNumberGeneratorInterface;
use Demo\IdServicesBundle\Lib\Exceptions\InvalidFormatException;
use Demo\IdServicesBundle\Lib\Classes\Utils\Utils;

class SaIdNumberGenerator implements IdNumberGeneratorInterface
{
    const DATE_REG_EXP = "/^\\d{6}$/";
    private $dateOfBirth;
    private $gender;
    private $origin;
    private $racialIdentifier;
    private $checkBit;
    
    /**
     * The constructor of the SaIdNumberGenerator Class
     * @param string $dateOfBirth The provided date of birth
     * @throws InvalidFormatException In case invalid parameter are provided
     */
    public function __construct($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;
        $this->checkDateOfBirth();
    }
    
    /**
     * Generates a valid South African ID number using the provided data
     * @param string $gender The gender to generate the ID with
     * @param string $origin The origin to generate the ID with
     * @throws InvalidFormatException In case invalid parameter are provided
     * @throws Exception In case any other type of issue
     * @return string $idNumber The generated ID number
     */
    public function generateIdNumber($gender, $origin)
    {
        $this->checkGender($gender);
        $this->checkOrigin($origin);
        $this->generateGender($gender);
        $this->generateOrigin($origin);
        $this->generateRacialIdentifier();
        $this->generateCheckbit();
        
        return sprintf("%s%s%s%s%s", $this->dateOfBirth, $this->gender,
                            $this->origin, $this->racialIdentifier, $this->checkBit);
    }
    
    /**
     * Generates the ID number's check bit
     */
    private function generateCheckbit()
    {
        $data = sprintf("%s%s%s%s", $this->dateOfBirth, $this->gender,
                            $this->origin, $this->racialIdentifier);
        $this->checkBit = Utils::generateCheckBit($data);
    }
    
    /**
     * Generates the gender section of the ID number number
     * @param string $gender The gender to generate the ID with
     */
    private function generateGender($gender)
    {
        if (strtolower($gender) == "male"){
            $this->gender = mt_rand(5000, 9999);
        } else {
            $this->gender = str_pad(mt_rand(0, 4999), 4, "0", STR_PAD_LEFT);
        }
    }
    
    /**
     * Generates the origin section of the ID number number
     * @param string $origin The origin to generate the ID number with
     */
    private function generateOrigin($origin)
    {
        $this->origin = $origin === "local" ? 0 : 1;
    }
    
    /**
     * Generates the racial identifier of the ID number
     */
    private function generateRacialIdentifier()
    {
        $this->racialIdentifier = mt_rand(8, 9);
    }
    
    /**
     * Checks the provided gender
     * @param string $gender The gender to generate the ID number with
     */
    private function checkGender($gender)
    {
        if (strtolower($gender) !== "male" && strtolower($gender) !== "female"){
            throw new InvalidFormatException("The gender must be either 'male' OR 'female'");
        }
    }
    
    /**
     * Checks the provided origin
     * @param string $origin The origin to generate the ID number with
     */
    private function checkOrigin($origin)
    {
        if (strtolower($origin) !== "local" && strtolower($origin) !== "foreign"){
            throw new InvalidFormatException("The origin must be either 'local' OR foreign");
        }
    }
    
    /**
     * Checks the provided date of birth
     */
    private function checkDateOfBirth()
    {
        if (strlen($this->dateOfBirth) != 6){
            throw new InvalidFormatException("The date of birth section of the ID number is invalid, it must be 6 numbers long");
        }
        if (!preg_match(self::DATE_REG_EXP, $this->dateOfBirth)){
            throw new InvalidFormatException("The date of birth section of the ID number is invalid, it must only contains numbers");
        }
    }
}