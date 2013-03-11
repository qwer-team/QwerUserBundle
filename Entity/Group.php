<?php

namespace Qwer\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\Group as FOSGroup;

/**
 * Groupp
 */
class Group extends FOSGroup
{
    /**
     *
     * @var string 
     */
    private $description;
    
    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

}
