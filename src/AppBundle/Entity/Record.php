<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RecordRepository")
 * @ORM\Table(name="record")
 */
class Record
{
    const NUM_ITEMS = 10;

    /**
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Artist", inversedBy="records")
     * @ORM\JoinColumn(name="artist_id", referencedColumnName="id", nullable=FALSE, onDelete="CASCADE")
     */
    private $artist;

    /**
     * @ORM\ManyToOne(targetEntity="Album", inversedBy="records")
     * @ORM\JoinColumn(name="album_id", referencedColumnName="id", nullable=TRUE, onDelete="CASCADE")
     */
    private $album;

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
     * Set name.
     * @param string $name
     * @return Record
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get artist.
     * @return Artist
     */
    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * Set album.
     * @param Album $album
     * @return Record
     */
    public function setAlbum(Album $album = null)
    {
        $this->album = $album;
        return $this;
    }

    /**
     * Get album.
     * @return Album
     */
    public function getAlbum()
    {
        return $this->album;
    }
}
