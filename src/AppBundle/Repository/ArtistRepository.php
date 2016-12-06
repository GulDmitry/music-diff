<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Artist;
use Doctrine\ORM\EntityRepository;

class ArtistRepository extends EntityRepository
{
    /**
     * Find artist by case insensitive name.
     * @param string $name
     * @return Artist|null
     */
    public function findArtistByName($name)
    {
        $qb = $this->createQueryBuilder('a');

        $qb->where('lower(a.name) = lower(:name)')
            ->setMaxResults(1)
            ->setParameter('name', $name);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
