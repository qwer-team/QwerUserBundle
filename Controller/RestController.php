<?php

namespace Qwer\UserBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use JMS\DiExtraBundle\Annotation as DI;

class RestController extends FOSRestController
{
    /**
     * @DI\Inject("jms_serializer")
     * @var \JMS\Serializer\Serializer 
     */
    public $serializer;
    
    protected function deserializeData($paramName, $type)
    {
        $request = $this->getRequest();
        $data = $request->get($paramName);
        $format = $this->getRequest()->get("_format");

        $deserialized = $this->serializer->deserialize($data, $type, $format);

        return $deserialized;
    }
}