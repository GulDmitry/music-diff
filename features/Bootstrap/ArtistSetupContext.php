<?php

namespace Features\Bootstrap;

use AppBundle\Entity\Artist;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Doctrine\Bundle\DoctrineBundle\Registry;

class ArtistSetupContext implements Context
{
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @param Registry $doctrine
     */
    public function __construct(Registry $doctrine)
    {
        $this->registry = $doctrine;
    }

    /**
     * @Given there are Artists with the following details:
     * @param TableNode $artists
     */
    public function thereAreArtistsWithTheFollowingDetails(TableNode $artists)
    {
        $em = $this->registry->getManager();

        foreach ($artists->getColumnsHash() as $key => $val) {
            $artist = new Artist($val['name']);
            $artist->setCountry($val['country']);

            $em->persist($artist);
        }
        $em->flush();
    }
}
