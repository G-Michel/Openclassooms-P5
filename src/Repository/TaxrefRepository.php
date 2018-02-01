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


    public function findByFrType(int $offset = -1, int $limit = Taxref::NUM_ITEMS)
    {
        // ['B','D','P','I','J','X','W']
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            "SELECT t,
            CASE
                WHEN t.frType in ('P') THEN 1
                ELSE 2
            END AS presence
            FROM App\Entity\Taxref t
            INNER JOIN App\Entity\Bird b WHERE t.id = b.taxref
            ORDER BY presence ASC
            "
        );

        $findResults = $query->execute();

        $limit = $limit + $offset + 1;
        foreach ($findResults as $k => $result) {
            if ($offset < $k && $k < $limit) {
                $results[] = $result[0];
            }
        }
        return $results;
    }
}
