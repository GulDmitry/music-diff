<?php

namespace AppBundle\Controller;

use AppBundle\Entity\{
    Album, Artist, ArtistGenre, Record
};
use Doctrine\DBAL\Cache\QueryCacheProfile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     * @Route("/", name="homepage")
     *
     * // TODO: test react router, remove in future.
     * @Route("/admin", name="admin")
     * @Route("/genre/{genre}", name="genre")
     * @Route("/genre/{genre}/{release}", name="genre_release")
     * @Route("/list", name="list")
     * @Route("/login-temp", name="temp_login")
     */
    public function indexAction(Request $request)
    {
        $this->testDbScheme();
        $rawQueryResult = $this->testRawQueryCache();

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
            'query_result' => count($rawQueryResult),
        ]);
    }

    private function testRawQueryCache()
    {
        /* @var \Doctrine\DBAL\Connection $connection */
        $connection = $this->getDoctrine()->getConnection();
        $cacheProfile = new QueryCacheProfile(
            5, // 0/null for infinite cache.
            'query_cache_key',
            $this->container->get('doctrine_cache.default_query_cache')
        );

        $sql = 'SELECT * FROM `artist`';
        $stmt = $connection->executeCacheQuery($sql, [], [], $cacheProfile);
        $result = $stmt->fetchAll();
        $stmt->closeCursor(); // At this point the result is cached.

        return $result;
    }

    private function testDbScheme()
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

        $em->persist((new ArtistGenre())->setGenre('Metal')->setArtist($artist));
        $em->persist((new ArtistGenre())->setGenre('Symphonic metal')->setArtist($artist));
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
                $this->get('logger')->debug('Record from Album ' . $record->getName());
            }
        }

        /* @var Record $record */
        foreach ($newArt->getRecords() as $record) {
            $this->get('logger')->debug('Record from Artist ' . $record->getName());
        }

        /* @var ArtistGenre $genre */
        foreach ($newArt->getGenres() as $genre) {
            $this->get('logger')->debug('Genre ' . $genre->getGenre());
        }
    }
}
