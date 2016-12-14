<?php

namespace MusicDiff\Entity;

class Album implements EntityInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @param array $type Album's type like `ep`, `album`.
     */
    private $types = [];

    /**
     * @param int|null $length Number of records in the album.
     */
    private $length;

    /**
     * @param \DateTimeImmutable|null $releaseDate Release date.
     */
    private $releaseDate;

    /**
     * @param string $name Album's title.
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Set the album's name.
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Get the album's name.
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set album's types.
     * @param array $types
     */
    public function setTypes(array $types)
    {
        array_walk($types, function (&$val) {
            $val = strtolower($val);
        });
        $this->types = $types;
    }

    /**
     * Get album's types.
     * @return array
     */
    public function getTypes(): array
    {
        return $this->types;
    }

    /**
     * Set number of records in album.
     * @param int $length
     */
    public function setLength(int $length)
    {
        $this->length = $length;
    }

    /**
     * Get number of records in album.
     * @return integer|null
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Set release date.
     * @param string $date
     */
    public function setReleaseDate(string $date)
    {
        $this->releaseDate = (new \DateTimeImmutable($date))->format(EntityInterface::DATA_FORMAT);
    }

    /**
     * Get release date.
     * @return string|null
     */
    public function getReleaseDate()
    {
        return $this->releaseDate;
    }
}
