<?php

namespace Demo\IdServicesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\Serializer\SerializerBuilder;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IdServicesController extends Controller
{
    /**
     * @return JsonResponse
     *
     * @ApiDoc(
     *  description="Generate a valid South African ID number",
     *  statusCodes={
     *      200="Returned when successful",
     *      500="Returned if an error occurred"
     *  }
     * )
     */
    public function generateIdNumberAction()
    {
        return $this->render('IdServicesBundle:Default:index.html.twig', array('name' => "123"));
    }
    
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return JsonResponse
     *
     * @ApiDoc(
     *  description="Check if an ID number is a valid South African ID number",
     *  filters={
     *      {"name"="idNumber", "dataType"="string"}
     *  },
     *  statusCodes={
     *      200="Returned when successful",
     *      400="Returned if the inputs are invalid",
     *      500="Returned if an error occurred"
     *  }
     * )
     */
    public function checkIdNumberAction(Request $request)
    {
        return $this->render('IdServicesBundle:Default:index.html.twig', array('name' => "123"));
    }
}
