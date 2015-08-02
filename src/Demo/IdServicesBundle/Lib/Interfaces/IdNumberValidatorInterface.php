<?php

namespace Demo\IdServicesBundle\Lib\Interfaces;

interface IdNumberValidatorInterface
{
    public function checkIdNumber($dateOfBirth, $gender, $origin);
}