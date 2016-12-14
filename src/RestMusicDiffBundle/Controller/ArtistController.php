<?php

namespace RestMusicDiffBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use MusicDiff\Collection\Collection;
use MusicDiff\Collection\CollectionInterface;
use MusicDiff\Collection\Converter\ArrayConverter;
use MusicDiff\DataProvider\Doctrine;
use MusicDiff\Entity\Artist;
use MusicDiff\Helper\DiffCollection;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
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

        $restoredCollection = $this->restoreCollection($initCollection);

        if ($initCollection->getStorage()->serialize() === $restoredCollection->getStorage()->serialize()) {
            throw new HttpException(400, "Artist ${name} is not found.");
        }

        $arrayCollection = (new ArrayConverter())->fromCollection($restoredCollection);

        $view = $this->view($arrayCollection, 200);
        return $this->handleView($view);
    }

    /**
     * Calculate difference by array collection.
     * @param Request $request
     * @return Response
     */
    public function postDifferenceAction(Request $request)
    {
        $arrayConverter = new ArrayConverter();
        $arrayCollection = json_decode($request->get('collection'), true);
        $collection = $arrayConverter->toCollection($arrayCollection);

        $restoredCollection = $this->restoreCollection($collection);

        $diffCollection = (new DiffCollection())->calculateDiff($collection, $restoredCollection);

        $arrayDiffCollection = $arrayConverter->fromCollection($diffCollection);

        $view = $this->view($arrayDiffCollection, 200);
        return $this->handleView($view);
    }

    /**
     * Restore the collection.
     * @param CollectionInterface $initColl
     * @return CollectionInterface
     */
    private function restoreCollection(CollectionInterface $initColl): ?CollectionInterface
    {
        $musicDiff = $this->get('music_diff');
        $musicDiff->setInitCollection($initColl);

        return $musicDiff->restoreCollection();
    }
}
