<?php

namespace App\Repository;

use App\Entity\Observation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ObservationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Observation::class);
    }

    public function findByStatus($limit)
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.user', 'o_u')
            ->addSelect('o_u')
            ->leftJoin('o_u.picture', 'o_u_p')
            ->addSelect('o_u_p')
            ->leftJoin('o.bird', 'o_b')
            ->addSelect('o_b')
            ->leftJoin('o_b.taxref', 'o_b_t')
            ->addSelect('o_b_t')
            ->leftJoin('o_b_t.picture', 'o_b_t_p')
            ->addSelect('o_b_t_p')
            ->where('o.status = 1')
            ->orderBy('o.dateObs', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

        public function findByUser($limit,$username)
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.user','u')
            ->where('u.username = :username')
            ->setParameter('username',$username)
            ->orderBy('o.dateAdd', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

        public function findToValid($limit)
    {
        return $this->createQueryBuilder('o')
            ->where('o.status = 0')
            ->orderBy('o.dateAdd', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

}
