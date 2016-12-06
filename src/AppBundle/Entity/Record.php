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
     * @ORM\Column(type="string", nullable=FALSE)
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
     * Record constructor.
     * @param string $name
     * @param Artist $artist
     * @param Album $album
     */
    public function __construct(string $name, Artist $artist, Album $album)
    {
        $this->name = $name;
        $this->artist = $artist;
        $this->album = $album;
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
     * Get album.
     * @return Album
     */
    public function getAlbum()
    {
        return $this->album;
    }
}
