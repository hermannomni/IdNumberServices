<?php

namespace Demo\IdServicesBundle\Lib\Exceptions;

class InvalidFormatException extends \Exception
{
    private $data;
    
    public function __construct ($message = "", $data = array(), $code = 0, \Exception $previous = NULL)
    {
        parent::__construct($message, $code, $previous);
        $data['message'] = $message;
        $this->data = $data;
    }
    
    public function getErrorData()
    {
        return $this->data;
    }
}