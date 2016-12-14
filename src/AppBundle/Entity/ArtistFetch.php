<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArtistFetchRepository")
 * @ORM\Table(name="artist_fetch")
 */
class ArtistFetch
{
    const NUM_ITEMS = 10;

    /**
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=FALSE)
     * @Assert\DateTime()
     */
    private $lastFetch;

    /**
     * @ORM\ManyToOne(targetEntity="Artist")
     * @ORM\JoinColumn(name="artist_id", referencedColumnName="id", nullable=FALSE, onDelete="CASCADE")
     */
    private $artist;

    /**
     * ArtistUpdate constructor.
     * @param Artist $artist
     * @param \DateTime $lastFetch
     */
    public function __construct(Artist $artist, \DateTime $lastFetch)
    {
        $this->lastFetch = $lastFetch;
        $this->artist = $artist;
    }

    /**
     * Get id.
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get lastUpdate.
     * @return \DateTime
     */
    public function getLastFetch()
    {
        return $this->lastFetch;
    }

    /**
     * Get artist.
     * @return \AppBundle\Entity\Artist
     */
    public function getArtist()
    {
        return $this->artist;
    }
}
