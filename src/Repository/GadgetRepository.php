<?php

namespace App\Repository;

use App\Entity\Gadget;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Gadget>
 */
class GadgetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gadget::class);
    }

    public function save(Gadget $gadget, bool $flush = false): void
    {
        $this->getEntityManager()->persist($gadget);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Gadget $gadget, bool $flush = false): void
    {
        $this->getEntityManager()->remove($gadget);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
