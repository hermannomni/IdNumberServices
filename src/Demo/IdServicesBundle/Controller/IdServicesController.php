<?php

namespace Demo\IdServicesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\Serializer\SerializerBuilder;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Demo\IdServicesBundle\Lib\Exceptions\InvalidFormatException;
use Demo\IdServicesBundle\Lib\Exceptions\IdValidationException;
use Demo\IdServicesBundle\Lib\Classes\SaIdNumberValidator\SaIdNumberValidator;
use Demo\IdServicesBundle\Lib\Classes\SaIdNumberGenerator\SaIdNumberGenerator;

class IdServicesController extends Controller
{
    /**
     * @return JsonResponse
     *
     * @ApiDoc(
     *  description="Generate a valid South African ID number",
     *  filters={
     *      {"name"="dateOfBirth", "dataType"="string"},
     *      {"name"="gender", "dataType"="string"},
     *      {"name"="origin", "dataType"="string"}
     *  },
     *  statusCodes={
     *      200="Returned when successful",
     *      400="Returned if the inputs are invalid",
     *      500="Returned if an error occurred"
     *  }
     * )
     */
    public function generateIdNumberAction($dateOfBirth, $gender, $origin)
    {
        $data = array();
        
        try{
            $saIdNumberGenerator = new SaIdNumberGenerator($dateOfBirth);
            $idNumber = $saIdNumberGenerator->generateIdNumber($gender, $origin);
            $httpCode = 200;
            $message = "The ID number has been generated successfully";
            $data = array(
                "idNumber"  =>  $idNumber
            );
        } catch(InvalidFormatException $ex) {
            $this->addErrorToLog("An InvalidFormat Exception has occured", $ex);
            $httpCode = 400;
            $message = $ex->getMessage();
        } catch(\Exception $ex) {
            $this->addErrorToLog("An Unknown Exception has occured", $ex);
            $httpCode = 500;
            $message = "An unknown error has occured, please try again later";
        }
        
        return $this->apiOutput(
            array(
                "status"    =>  $httpCode,
                "message"   =>  $message,
                "data"      =>  $data
            ),
            $httpCode
        );
    }
    
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return JsonResponse
     *
     * @ApiDoc(
     *  description="Check if an ID number is a valid South African ID number",
     *  filters={
     *      {"name"="idNumber", "dataType"="string"},
     *      {"name"="dateOfBirth", "dataType"="string"},
     *      {"name"="gender", "dataType"="string"},
     *      {"name"="origin", "dataType"="string"}
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
        $idNumber = $request->get("idNumber");
        $dateOfBirth = $request->get("dateOfBirth");
        $gender = $request->get("gender");
        $origin = $request->get("origin");
        $data = array();
        
        try{
            $saIdNumberValidator = new SaIdNumberValidator($idNumber);
            $saIdNumberValidator->checkIdNumber($dateOfBirth, $gender, $origin);
            $httpCode = 200;
            $message = "The ID number and the details provided matches";
        } catch(InvalidFormatException $ex) {
            $this->addErrorToLog("An InvalidFormat Exception has occured", $ex);
            $httpCode = 400;
            $message = $ex->getMessage();
        } catch(IdValidationException $ex) {
            $this->addErrorToLog("An IdValidation Exception has occured", $ex);
            $httpCode = 500;
            $message = $ex->getMessage();
        } catch(\Exception $ex) {
            $this->addErrorToLog("An Unknown Exception has occured", $ex);
            $httpCode = 500;
            $message = "An unknown error has occured, please try again later";
        }
        
        return $this->apiOutput(
            array(
                "status"    =>  $httpCode,
                "message"   =>  $message,
                "data"      =>  $data
            ),
            $httpCode
        );
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
