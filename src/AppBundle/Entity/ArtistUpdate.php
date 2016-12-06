<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArtistUpdateRepository")
 * @ORM\Table(name="artist_update")
 */
class ArtistUpdate
{
    const NUM_ITEMS = 10;

    /**
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     */
    private $lastUpdate;

    /**
     * @ORM\ManyToOne(targetEntity="Artist")
     * @ORM\JoinColumn(name="artist_id", referencedColumnName="id", nullable=FALSE, onDelete="CASCADE")
     */
    private $artist;

    /**
     * @param Artist $artist
     */
    public function __construct(Artist $artist)
    {
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
     * Set lastUpdate.
     * @param \DateTime $lastUpdate
     * @return ArtistUpdate
     */
    public function setLastUpdate($lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;
        return $this;
    }

    /**
     * Get lastUpdate.
     * @return \DateTime
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
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
