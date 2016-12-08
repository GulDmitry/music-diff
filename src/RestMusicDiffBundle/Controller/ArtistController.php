<?php

namespace RestMusicDiffBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use MusicDiff\Collection\Collection;
use MusicDiff\Collection\Converter\ArrayConverter;
use MusicDiff\DataProvider\Doctrine;
use MusicDiff\Entity\Artist;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Length;

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
        $form = $this->createFormBuilder([], ['csrf_protection' => false])
            ->add('artist-name', TextType::class, [
                'constraints' => [new Length(['min' => 2, 'max' => 255])],
            ])
            ->getForm()
            ->submit([
                'artist-name' => $name,
            ]);

        if (!$form->isValid()) {
            return $this->handleView($this->view($form->getErrors(), 400));
        }

        $initCollection = new Collection();
        $initCollection->addArtist(new Artist($name));

        $musicDiff = $this->get('music_diff');
        $musicDiff->setInitCollection($initCollection);

        // TODO: update metadata every * days.
        $restoredCollection = $musicDiff->restoreCollection();

        // TODO: If no artist found.
//        throw new HttpException(400, "New comment is not valid.");

        (new Doctrine($this->getDoctrine()))->saveCollectionToDB($restoredCollection);

        $arrayCollection = (new ArrayConverter())->fromCollection($restoredCollection);

        $view = $this->view($arrayCollection, 200);
        return $this->handleView($view);
    }
}
