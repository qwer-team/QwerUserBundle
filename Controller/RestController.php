<?php

namespace Qwer\UserBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use JMS\DiExtraBundle\Annotation as DI;
use Qwer\UserBundle\Entity\Response;

class RestController extends FOSRestController
{

    /**
     * @DI\Inject("jms_serializer")
     * @var \JMS\Serializer\Serializer 
     */
    public $serializer;

    protected function deserializeData($type, $paramName = "data")
    {
        $request = $this->getRequest();
        $data = $request->get($paramName);
        $format = $this->getRequest()->get("_format");

        $deserialized = $this->serializer->deserialize($data, $type, $format);

        return $deserialized;
    }

    protected function view($data = null, $statusCode = null, array $headers = array())
    {
        $response = new Response();
        $response->data = $data;
        return parent::view($response, $statusCode, $headers);
    }

}