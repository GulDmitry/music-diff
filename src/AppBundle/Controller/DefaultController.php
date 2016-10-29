<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Album;
use AppBundle\Entity\Artist;
use AppBundle\Entity\Record;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        /* 5.6 -> 7
         *
         * 2 <=> 1; // 1
         * $a[1] ?? 'no'
         * function():array
         * $app->setLogger(new class implements Logger {
                public function log(string $msg) {
                    echo $msg;
                }
            });
         * Unicode "\u{aa}";
         * $closure->call(new A) - call the func with new $this
         * unserialize - allowed_classes
         * use namespace\{ClassA, ClassB, ClassC as C};
         */

        $em = $this->getDoctrine()->getManager();

        $record = (new Record())->setName('Martyr');
        $record2 = (new Record())->setName('Prophecy');

        $album = (new Album())->setName('Mirror Mirror');
        $album2 = (new Album())->setName('Imagination');

        $artist = (new Artist())->setName('Blind Guardian');

        $album->setArtist($artist);
        $album2->setArtist($artist);

        $record->setArtist($artist)->setAlbum($album);
        $record2->setArtist($artist);

        $em->persist($record);
        $em->persist($record2);
        $em->persist($album);
        $em->persist($album2);
        $em->persist($artist);

        $em->flush();

        $em->detach($artist);
        $em->detach($album);
//        $em->clear();

        $newArt = $em->getRepository('AppBundle:Artist')
            ->find($artist->getId());

        /* @var Album $album */
        foreach ($newArt->getAlbums() as $album) {
            /* @var Record $record */
            foreach ($album->getRecords() as $record) {
                var_dump($record->getName());
            }
        }

        /* @var Record $record */
        foreach ($newArt->getRecords() as $record) {
            var_dump($record->getName());
        }

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
        ]);
    }
}
