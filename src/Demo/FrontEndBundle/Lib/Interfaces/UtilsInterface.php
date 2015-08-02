<?php

namespace Demo\FrontEndBundle\Lib\Interfaces;

interface UtilsInterface
{
    public function httpRequests($requestUrl, $params = array(), $method = "post");
}