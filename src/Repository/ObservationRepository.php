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

    public function findObservationsWithLimit($limit)
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

    // public function findByUser($limit,$username)
    // {
    //     return $this->createQueryBuilder('o')
    //         ->leftJoin('o.user','u')
    //         ->where('u.username = :username')
    //         ->setParameter('username',$username)
    //         ->orderBy('o.dateAdd', 'DESC')
    //         ->setMaxResults($limit)
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }

    // public function findToValid($limit)
    // {
    //     return $this->createQueryBuilder('o')
    //         ->where('o.status = 0')
    //         ->orderBy('o.dateAdd', 'DESC')
    //         ->setMaxResults($limit)
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }

    public function findByUser($user, $limit = null)
    {
        $qb = $this->createQueryBuilder('o')
            ->select('o.id','o.dateObs','o.status','l.address')
            ->leftJoin('o.location','l')
            ->where('o.user = :user')
            ->setParameter('user',$user)
            ->orderBy('o.dateAdd', 'DESC');
        if ($limit) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()
                  ->getResult();
    }

    public function findEqualToStatus($status,$limit = null)
    {
        $qb = $this->createQueryBuilder('o')
            ->select('o')
            ->leftJoin('o.location','o_l')
            ->leftJoin('o.user','o_u')
            ->leftJoin('o.bird','o_b')
            ->leftJoin('o_b.taxref','o_b_t')
            ->addselect('o_l','o_u','o_b','o_b_t')
            ->where('o.status = :status')
            ->setParameter('status',$status)
            ->orderBy('o.dateAdd', 'DESC');
        if ($limit) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()
                  ->getResult();
    }

    public function findLessThanOrEqualStatus($status,$limit = null)
    {
        $qb = $this->createQueryBuilder('o')
            ->where('o.status = :status')
            ->orwhere('o.status < :status')
            ->setParameter('status',$status)
            ->orderBy('o.dateAdd', 'DESC');
        if ($limit) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()
                  ->getResult();
    }

}
