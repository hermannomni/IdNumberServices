<?php

namespace Demo\FrontEndBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Demo\FrontEndBundle\Lib\Exceptions\MalformatedResponseException;

class FrontEndController extends Controller
{
    public function indexAction()
    {
        return $this->render('FrontEndBundle:frontEnd:index.html.twig');
    }
}
