<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AlbumRepository")
 * @ORM\Table(name="album")
 */
class Album
{
    const NUM_ITEMS = 10;

    /**
     * Possible album types.
     */
    const TYPES = [
        'nat',
        'album',
        'single',
        'ep',
        'compilation',
        'soundtrack',
        'spokenword',
        'interview',
        'audiobook',
        'live',
        'remix',
        'demo',
        'other',
    ];

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
     * @ORM\Column(type="simple_array", nullable=TRUE)
     */
    private $types = [];

    /**
     * @ORM\Column(type="smallint", nullable=TRUE)
     */
    private $length;

    /**
     * @ORM\Column(type="date", nullable=TRUE)
     * @Assert\Date()
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="Artist", inversedBy="albums")
     * @ORM\JoinColumn(name="artist_id", referencedColumnName="id", nullable=FALSE, onDelete="CASCADE")
     */
    private $artist;

    /**
     * @ORM\OneToMany(targetEntity="Record", mappedBy="album")
     */
    private $records;

    /**
     * Album constructor.
     * @param string $name
     * @param Artist $artist
     */
    public function __construct(string $name, Artist $artist)
    {
        $this->name = $name;
        $this->artist = $artist;
        $this->records = new ArrayCollection();
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
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set album types.
     * @param array $types
     * @return Album
     */
    public function setTypes(array $types)
    {
        array_walk($types, function (&$val) {
            $val = strtolower($val);
        });
        if (count(array_diff($types, self::TYPES))) {
            throw new \InvalidArgumentException('Invalid type. Possible values ' . var_export(self::TYPES, true));
        }

        $this->types = $types;

        return $this;
    }

    /**
     * Get type.
     * @return array
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * Set length.
     * @param integer $length
     * @return Album
     */
    public function setLength($length)
    {
        $this->length = $length;
        return $this;
    }

    /**
     * Get length.
     * @return integer
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Set release date.
     * @param \DateTime $date
     * @return Album
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get release date.
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
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
     * Get records.
     * @return ArrayCollection
     */
    public function getRecords()
    {
        return $this->records;
    }
}
