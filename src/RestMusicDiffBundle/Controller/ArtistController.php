<?php

namespace RestMusicDiffBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use MusicDiff\Collection\Collection;
use MusicDiff\Collection\Converter\ArrayConverter;
use MusicDiff\DataProvider\Doctrine;
use MusicDiff\Entity\Artist;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Rest\Version("v1")
 * @package RestMusicDiffBundle\Controller
 */
class ArtistController extends FOSRestController
{
    /**
     * Find Artist and its data by name.
     * @param string $name
     * @return Response
     */
    public function getArtistAction(string $name)
    {
        $initCollection = new Collection();
        $initCollection->addArtist(new Artist($name));

        $musicDiff = $this->get('music_diff');
        $musicDiff->setInitCollection($initCollection);

        $restoredCollection = $musicDiff->restoreCollection();

        (new Doctrine($this->getDoctrine()))->saveCollectionToDB($restoredCollection);

        $arrayCollection = (new ArrayConverter())->fromCollection($restoredCollection);

        $view = $this->view($arrayCollection, 200);
        return $this->handleView($view);
    }
}
