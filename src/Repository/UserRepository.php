<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Auth;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

class UserRepository extends EntityRepository implements UserLoaderInterface
{

      public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username OR u.mail = :mail')
            ->setParameter('username', $username)
            ->setParameter('mail', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function tokenComfirm($username,$token)
    {
        return $this->createQueryBuilder('u')
            ->innerJoin(Auth::class,'a')
            ->where('u.username = :username AND a.comfirmedToken = :token')
            ->setParameter('username',$username)
            ->setParameter('token',$token)
            ->getQuery()
            ->getOneOrNullResult();

    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('u')
            ->where('u.something = :value')->setParameter('value', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
