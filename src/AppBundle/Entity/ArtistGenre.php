<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArtistGenreRepository")
 * @ORM\Table(name="artist_genre")
 */
class ArtistGenre
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
    private $genre;

    /**
     * @ORM\ManyToOne(targetEntity="Artist", inversedBy="genres")
     * @ORM\JoinColumn(name="artist_id", referencedColumnName="id", nullable=FALSE, onDelete="CASCADE")
     */
    private $artist;

    /**
     * ArtistGenre constructor.
     * @param string $genre
     * @param Artist $artist
     */
    public function __construct(string $genre, Artist $artist)
    {
        $this->genre = $genre;
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
     * Get genre.
     * @return string
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Get artist.
     * @return Artist
     */
    public function getArtist()
    {
        return $this->artist;
    }
}
