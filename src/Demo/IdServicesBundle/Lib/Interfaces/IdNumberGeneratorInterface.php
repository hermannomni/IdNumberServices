<?php

namespace Demo\IdServicesBundle\Lib\Interfaces;

interface IdNumberGeneratorInterface
{
    public function generateIdNumber($gender, $origin);
}