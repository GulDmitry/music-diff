<?php

namespace MusicDiff\Entity;

class Artist implements EntityInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string|null
     */
    private $country;

    /**
     * @var \DateTimeImmutable|null $beginDate When the band was founded.
     */
    private $beginDate;

    /**
     * @var \DateTimeImmutable|null $endDate When the band ended career.
     */
    private $endDate;

    /**
     * @var array
     */
    private $genres = [];

    /**
     * @param string $name Artist's name.
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Set Artist's name.
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Get Artist's name.
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the country where the band was founded.
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * Get the country where the band was founded.
     * @return string|null
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set founding date.
     * @param string $beginDate
     */
    public function setBeginDate(string $beginDate)
    {
        $this->beginDate = (new \DateTimeImmutable($beginDate))->format(EntityInterface::DATA_FORMAT);
    }

    /**
     * Get founding date.
     * @return string|null
     */
    public function getBeginDate()
    {
        return $this->beginDate;
    }

    /**
     * Set end date.
     * @param string $endDate
     */
    public function setEndDate(string $endDate)
    {
        $this->endDate = (new \DateTimeImmutable($endDate))->format(EntityInterface::DATA_FORMAT);
    }

    /**
     * Get end date.
     * @return string|null
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set the band's genres.
     * @param array $genres
     */
    public function setGenres(array $genres)
    {
        $this->genres = $genres;
    }

    /**
     * Get the band's genres.
     * @return array
     */
    public function getGenres(): array
    {
        return $this->genres;
    }
}
