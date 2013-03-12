<?php

namespace Qwer\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

class RoleRepository extends EntityRepository
{

    public function findAllByIds($ids)
    {
        $query = $this->_em
                      ->createQuery("SELECT role FROM QwerUserBundle:Role role".
                                    " WHERE role.id in (:ids)");
        $query->setParameter("ids", implode(",", $ids));
        
        $roles = $query->getResult();
        return $roles;
    }

}