<?php

namespace Demo\IdServicesBundle\Lib\Classes\SaIdNumberValidator;

use Demo\IdServicesBundle\Lib\Interfaces\IdNumberValidatorInterface;
use Demo\IdServicesBundle\Lib\Exceptions\InvalidFormatException;
use Demo\IdServicesBundle\Lib\Exceptions\IdValidationException;
use Demo\IdServicesBundle\Lib\Interfaces\UtilsInterface;


class SaIdNumberValidator implements IdNumberValidatorInterface
{
    const ID_NUMBER_REG_EXP = "/^\\d{13}$/";
    private $dateOfBirth;
    private $gender;
    private $origin;
    private $racialIdentifier;
    private $checkBit;
    private $utils;
    
    /**
     * The constructor of the SaIdNumberValidator Class
     * @param string $idNumber The ID number to validate data against
     * @throws InvalidFormatException In case invalid parameter are provided
     * @throws IdValidationException In case we encouter an issue while validating the ID number against the provided data
     */
    public function __construct(UtilsInterface $utils, $idNumber)
    {
        $this->utils = $utils;
        if (strlen($idNumber) == 13 && preg_match(self::ID_NUMBER_REG_EXP, $idNumber)){
            $this->dateOfBirth = substr($idNumber, 0, 6);
            $this->gender = substr($idNumber, 6, 4);
            $this->origin = substr($idNumber, 10, 1);
            $this->racialIdentifier = substr($idNumber, 11, 1);
            $this->checkBit = substr($idNumber, 12, 1);
            
            $this->checkIdNumberOrigin();
            $this->checkIdNumberRacialIdentifier();
            $this->validateIdNumberCheckBit($idNumber);
        } else {
            throw new InvalidFormatException("The Id number must be made of 13 numbers",
                array(
                    "idNumber"  =>  $idNumber
                )
            );
        }
    }
    
    /**
     * Check the ID number and the provided data for validity
     * @param string $dateOfBirth The date of birth to validate the ID number against
     * @param string $gender The gender (male or female) to validate the ID number against
     * @param string $origin The origin (local or foreign) to validate the ID number against
     * @throws InvalidFormatException In case invalid parameter are provided
     * @throws IdValidationException In case we encouter an issue while validating the ID number against the provided data
     * @throws Exception In case any other type of issue
     */
    public function checkIdNumber($dateOfBirth, $gender, $origin)
    {
        $this->checkDateOfBirth($dateOfBirth);
        $this->checkGenderInput($gender);
        $this->checkOriginInput($origin);
        $this->checkGender($gender);
        $this->checkOrigin($origin);
    }
    
    /**
     * Check the ID number and the provided origin for validity
     * @param string $origin The origin (local or foreign) to validate the ID number against
     * @throws IdValidationException In case we encouter an issue while validating the ID number against the provided origin
     */
    private function checkOrigin($origin)
    {
        if ($this->origin === "0" && strtolower($origin) === "foreign"){
            throw new IdValidationException("The origin provided and the origin indicator in the ID number do not match",
                array(
                    "originProvided"      =>  $origin,
                    "originInIdNumber"    =>  $this->origin
                )
            );
        }
        
        if ($this->origin === "1" && strtolower($origin) === "local"){
            throw new IdValidationException("The origin provided and the origin indicator in the ID number do not match",
                array(
                    "originProvided"      =>  $origin,
                    "originInIdNumber"    =>  $this->origin
                )
            );
        }
    }
    
    /**
     * Check the ID number and the provided gender for validity
     * @param string $gender The gender (male or female) to validate the ID number against
     * @throws IdValidationException In case we encouter an issue while validating the ID number against the provided gender
     */
    private function checkGender($gender)
    {
        if (intval($this->gender) >= 5000 && strtolower($gender) === "female"){
            throw new IdValidationException("The gender provided and the gender indicator in the ID number do not match",
                array(
                    "genderProvided"      =>  $gender,
                    "genderInIdNumber"    =>  $this->gender
                )
            );
        }
        
        if (intval($this->gender) < 5000 && strtolower($gender) === "male"){
            throw new IdValidationException("The gender provided and the gender indicator in the ID number do not match",
                array(
                    "genderProvided"      =>  $gender,
                    "genderInIdNumber"    =>  $this->gender
                )
            );
        }
    }
    
    /**
     * Check the ID number and the provided date of birth for validity
     * @param string $dateOfBirth The date of birth to validate the ID number against
     * @throws IdValidationException In case we encouter an issue while validating the ID number against the provided date of birth
     */
    private function checkDateOfBirth($dateOfBirth)
    {
        if ($this->dateOfBirth !== $dateOfBirth){
            throw new IdValidationException("The date of birth provided does not correspond to the date of birth in the ID number",
                array(
                    "dateProvided"      =>  $dateOfBirth,
                    "dateInIdNumber"    =>  $this->dateOfBirth
                )
            );
        }
    }
    
    /**
     * Check the ID number and the provided date of birth for validity
     * @param string $idNumber The ID number that will be used to compute the check bit
     * @throws IdValidationException In case we encouter an issue while validating the check bit
     */
    private function validateIdNumberCheckBit($idNumber)
    {
        $computedCheckBit = $this->utils->generateCheckBit($idNumber);
        if ($computedCheckBit !== intval($this->checkBit)){
            throw new InvalidFormatException("The check bit section of the ID number does not verify the data in the ID number",
                array(
                    "racialIdentifier"  =>  $this->racialIdentifier
                )
            );
        }
    }
    
    /**
     * Check the ID number's racial identifier for validity
     * @throws IdValidationException In case we encouter an issue while validating the racial identifier
     */
    private function checkIdNumberRacialIdentifier()
    {
        if ($this->racialIdentifier !== "8" && $this->racialIdentifier !== "9"){
            throw new InvalidFormatException("The racial identifier section of the ID number is not valid",
                array(
                    "racialIdentifier"  =>  $this->racialIdentifier
                )
            );
        }
    }
    
    /**
     * Check the ID number's origin (country id) for validity
     * @throws IdValidationException In case we encouter an issue while validating the origin
     */
    private function checkIdNumberOrigin()
    {
        if ($this->origin !== '0' && $this->origin !== '1'){
            throw new InvalidFormatException("The country ID section of the ID number is not valid",
                array(
                    "idNumber"  =>  $this->origin
                )
            );
        }
    }
    
    /**
     * Check the provided gender for validity
     * @param string $gender The provided provided gender
     * @throws IdValidationException In case we encouter an issue while validating the provided gender
     */
    private function checkGenderInput($gender)
    {
        if (strtolower($gender) !== "male" && strtolower($gender) !== "female"){
            throw new InvalidFormatException("The provided gender must be 'male' OR 'female'",
                array(
                    "providedGender"  =>  $gender
                )
            );
        }
    }
    
    /**
     * Check the provided origin for validity
     * @param string $origin The provided provided origin
     * @throws IdValidationException In case we encouter an issue while validating the provided origin
     */
    private function checkOriginInput($origin)
    {
        if (strtolower($origin) !== "local" && strtolower($origin) !== "foreign"){
            throw new InvalidFormatException("The provided origin must be 'local' OR 'foreign'",
                array(
                    "providedOrigin"  =>  $origin
                )
            );
        }
    }
}