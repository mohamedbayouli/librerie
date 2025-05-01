<?php

namespace App\Repository;

use App\Entity\Livre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Livre>
 */
class LivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }

    //    /**
    //     * @return Livre[] Returns an array of Livre objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Livre
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function findByFilters(?string $searchTerm = null, ?int $categoryId = null): array
    {
        $qb = $this->createQueryBuilder('l');
        
        // Filtre par terme de recherche (titre)
        if ($searchTerm) {
            $qb->andWhere('l.titre LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }
        
        // Filtre par catégorie
        if ($categoryId) {
            $qb->andWhere('l.cat_id = :categoryId')
               ->setParameter('categoryId', $categoryId);
        }
        
        // Tri par titre par défaut
        $qb->orderBy('l.titre', 'ASC');
        
        return $qb->getQuery()->getResult();
    }
}
