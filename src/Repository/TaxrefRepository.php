<?php

namespace App\Repository;

use App\Entity\Taxref;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TaxrefRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Taxref::class);
    }

    /**
     * @return Taxref[]
     */
    public function findBySearchQuery(array $searchTerms, int $limit = Taxref::NUM_ITEMS): array
    {

        if (0 === count($searchTerms)) {
            return [];
        }

        $queryBuilder = $this->createQueryBuilder('t');

        foreach ($searchTerms as $key => $term) {
            $queryBuilder
                // ->add('where', $queryBuilder->expr()->orX(
                //     // $queryBuilder->expr()->eq('t.nomVernType', ':t_'.$key),
                //     $queryBuilder->expr()->like('t.nomVernType', ':t_'.$key)
                // ))
                ->orWhere('t.nomVernType LIKE :t_'.$key)
                ->setParameter('t_'.$key, '%'.$term.'%')
            ;
        }

        return $queryBuilder
            // ->orderBy('p.publishedAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }


    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('t')
            ->where('t.something = :value')->setParameter('value', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
