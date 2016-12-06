<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArtistRepository")
 * @ORM\Table(name="artist")
 *
 * Defines the properties of the Post entity to represent the blog posts.
 * See http://symfony.com/doc/current/book/doctrine.html#creating-an-entity-class
 *
 * Tip: if you have an existing database, you can generate these entity class automatically.
 * See http://symfony.com/doc/current/cookbook/doctrine/reverse_engineering.html
 */
class Artist
{
    /**
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See http://symfony.com/doc/current/best_practices/configuration.html#constants-vs-configuration-options
     */
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
     * @ORM\Column(type="string", nullable=TRUE)
     * @Assert\Country()
     */
    private $country;

    /**
     * @ORM\Column(type="date", nullable=TRUE)
     * @Assert\Date()
     */
    private $beginDate;

    /**
     * @ORM\Column(type="date", nullable=TRUE)
     * @Assert\Date()
     */
    private $endDate;

    /**
     * @ORM\OneToMany(targetEntity="Album", mappedBy="artist")
     */
    private $albums;

    /**
     * @ORM\OneToMany(targetEntity="Record", mappedBy="artist")
     */
    private $records;

    /**
     * @ORM\OneToMany(targetEntity="ArtistGenre", mappedBy="artist")
     */
    private $genres;

    /**
     * Artist constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->albums = new ArrayCollection();
        $this->records = new ArrayCollection();
        $this->genres = new ArrayCollection();
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
     * Set country.
     * @param string $country
     * @return Artist
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Get country.
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set beginDate.
     * @param \DateTime $beginDate
     * @return Artist
     */
    public function setBeginDate(\DateTime $beginDate)
    {
        $this->beginDate = $beginDate;
        return $this;
    }

    /**
     * Get beginDate.
     * @return \DateTime
     */
    public function getBeginDate()
    {
        return $this->beginDate;
    }

    /**
     * Set endDate.
     * @param \DateTime $endDate
     * @return Artist
     */
    public function setEndDate(\DateTime $endDate)
    {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * Get endDate.
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Get albums.
     * @return ArrayCollection
     */
    public function getAlbums()
    {
        return $this->albums;
    }

    /**
     * Get records.
     * @return ArrayCollection
     */
    public function getRecords()
    {
        return $this->records;
    }

    /**
     * Get genres.
     * @return ArrayCollection
     */
    public function getGenres()
    {
        return $this->genres;
    }
}
