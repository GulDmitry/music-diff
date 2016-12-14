<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Album;
use Doctrine\ORM\EntityRepository;

class AlbumRepository extends EntityRepository
{
    /**
     * Find album by case insensitive name.
     * @param string $name
     * @return Album|null
     */
    public function findAlbumByName($name)
    {
        $qb = $this->createQueryBuilder('a');

        $qb->where('lower(a.name) = lower(:name)')
            ->setMaxResults(1)
            ->setParameter('name', $name);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
