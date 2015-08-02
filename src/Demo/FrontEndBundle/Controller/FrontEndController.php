<?php

namespace Demo\FrontEndBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Demo\FrontEndBundle\Entity\IdNumberServiceClient;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Demo\FrontEndBundle\Lib\Exceptions\MalformatedResponseException;
use Demo\FrontEndBundle\Lib\Classes\Utils\Utils;

class FrontEndController extends Controller
{
    public function indexAction()
    {
        return $this->render('FrontEndBundle:frontEnd:index.html.twig');
    }
    
    public function generateIdNumberAction()
    {
        $protocol = $this->container->getParameter('protocol');
        $httpHost = $this->container->getParameter('http_host');
        
        $request = $this->get("request");
        $dateOfBirth = $request->get("dateOfBirth");
        $gender = $request->get("gender");
        $origin = $request->get("origin");
        
        $utils = new Utils();
        
        $idNumberServiceClient = new IdNumberServiceClient($utils, $protocol, $httpHost);
        
        try{
            $results = $idNumberServiceClient->generateIdNumber($dateOfBirth, $gender, $origin);
        } catch(MalformatedResponseException $ex) {
            $this->addErrorToLog("A Malformated Response Exception has occured", $ex);
            $results = array(
                "status"    =>  500,
                "message"   =>  $ex->getMessage(),
                "data"      =>  array()
            );
            
        }
        
        return $this->apiOutput($results, $results["status"]);
    }
    
    public function checkIdNumberAction(Request $request)
    {
        $protocol = $this->container->getParameter('protocol');
        $httpHost = $this->container->getParameter('http_host');
        
        $idNumber = $request->get("idNumber");
        $dateOfBirth = $request->get("dateOfBirth");
        $gender = $request->get("gender");
        $origin = $request->get("origin");
        
        $utils = new Utils();
        
        $idNumberServiceClient = new IdNumberServiceClient($utils, $protocol, $httpHost);
        
        try{
            $results = $idNumberServiceClient->checkIdNumber($idNumber, $dateOfBirth, $gender, $origin);
        } catch(MalformatedResponseException $ex) {
            $this->addErrorToLog("A Malformated Response Exception has occured", $ex);
            $results = array(
                "status"    =>  500,
                "message"   =>  $ex->getMessage(),
                "data"      =>  array()
            );
            
        }
        
        return $this->apiOutput($results, $results["status"]);
    }
    
    /**
     * Formats the outputs
     * @param array $data The data to output
     * @param int $httpCode The HTTP code to return along with the request
     * @return Symfony\Component\HttpFoundation\Response
     */
    private function apiOutput($data, $httpCode)
    {
        $serializer = SerializerBuilder::create()->build();
        $content = $serializer->serialize(
            $data,
            'json'
        );
        
        return new Response($content, $httpCode, array(
                'Content-Type' => 'application/json',
                'Content-Length' => strlen($content)
            )
        );
    }
    
    /**
     * Add error details to the log for later processing
     * @param string $description The description of the error
     * @param mixed $errorObject The error that has occured
     */
    private function addErrorToLog($description, $errorObject)
    {
        $this->get('logger')->error($description . " {ex}", array('ex', $errorObject));
    }
}
