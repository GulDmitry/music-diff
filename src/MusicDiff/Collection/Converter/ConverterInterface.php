<?php

namespace MusicDiff\Collection\Converter;

use MusicDiff\Collection\CollectionInterface;

interface ConverterInterface
{
    /**
     * Convert the collection to external data format.
     * @param CollectionInterface $collection
     * @return mixed
     */
    public function fromCollection(CollectionInterface $collection);

    /**
     * Restore the collection for external data format.
     * @param mixed $data
     * @return CollectionInterface
     */
    public function toCollection($data): CollectionInterface;
}
