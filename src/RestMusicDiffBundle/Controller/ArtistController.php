<?php

namespace RestMusicDiffBundle\Controller;

use AppBundle\Entity\Artist;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Rest\Version("v1")
 * @package RestMusicDiffBundle\Controller
 */
class ArtistController extends FOSRestController
{
    /**
     * Find Artist and its albums by name.
     * @param $name
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getArtistAlbumsAction($name)
    {
        $artist = $this->getDoctrine()->getRepository('AppBundle:Artist')->findOneBy(['name' => $name]);
        if (!$artist instanceof Artist) {
            throw new NotFoundHttpException('Artist not found');
        }

        $view = $this->view($artist, 200);
        $view->setContext($view->getContext()->setGroups(['artist', 'album']));
        return $this->handleView($view);
    }

}
