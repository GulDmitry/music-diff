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
        'album',
        'ep',
        'demo',
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(type="string", nullable=TRUE)
     */
    private $type;

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
     * @ORM\JoinColumn(name="artist_id", referencedColumnName="id", nullable=FALSE)
     */
    private $artist;

    /**
     * @ORM\OneToMany(targetEntity="Record", mappedBy="album")
     */
    private $records;

    /**
     * Album constructor.
     */
    public function __construct()
    {
        $this->records = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Album
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
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
     * Set type
     *
     * @param string $type
     *
     * @return Album
     */
    public function setType($type)
    {
        $type = strtolower($type);
        if (!in_array($type, self::TYPES)) {
            throw new \InvalidArgumentException('Invalid type. Possible values ' . var_export(self::TYPES, true));
        }

        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set length
     *
     * @param integer $length
     *
     * @return Album
     */
    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * Get length
     *
     * @return integer
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Album
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set artist
     *
     * @param Artist $artist
     *
     * @return Album
     */
    public function setArtist(Artist $artist = null)
    {
        $this->artist = $artist;

        return $this;
    }

    /**
     * Get artist
     *
     * @return Artist
     */
    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * Add record
     *
     * @param Record $record
     *
     * @return Album
     */
    public function addRecord(Record $record)
    {
        $this->records[] = $record;

        return $this;
    }

    /**
     * Remove record
     *
     * @param Record $record
     */
    public function removeRecord(Record $record)
    {
        $this->records->removeElement($record);
    }

    /**
     * Get records
     *
     * @return ArrayCollection
     */
    public function getRecords()
    {
        return $this->records;
    }
}
