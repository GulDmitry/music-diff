<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Album;
use AppBundle\Entity\Artist;
use AppBundle\Entity\ArtistFetch;
use AppBundle\Entity\ArtistGenre;
use AppBundle\Entity\Record;
use Doctrine\DBAL\Cache\QueryCacheProfile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
//        $this->testDbScheme();
//        $rawQueryResult = $this->testRawQueryCache();

        $user = $this->get('security.token_storage')->getToken()->getUser();

        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
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
         * $app->setLogger(new class implements Logger {
                public function log(string $msg) {
                    echo $msg;
                }
            });
         * Unicode "\u{aa}";
         * $closure->call(new A) - call the func with new $this
         * unserialize - allowed_classes
         * use namespace\{ClassA, ClassB, ClassC as C};
         *
         * 7 -> 7.1
         * ?string - + null
         * :void
         * [$id1, $name1] = $data[0]; instead of list() + keys ["id" => $id1, "name" => $name1]
         *  public const PUBLIC_CONST_B = 2;
         * pseudo-type iterable
         * catch (FirstException | SecondException $e) {
         */

        $em = $this->getDoctrine()->getManager();

        $artist = new Artist('Blind Guardian');

        $album = new Album('Mirror Mirror', $artist);
        $album2 = new Album('Imagination', $artist);

        $record = new Record('Martyr', $artist, $album);
        $record2 = new Record('Prophecy', $artist, $album);

        $em->persist(new ArtistGenre('Metal', $artist));
        $em->persist(new ArtistGenre('Symphonic metal', $artist));
        $em->persist($record);
        $em->persist($record2);
        $em->persist($album);
        $em->persist($album2);
        $em->persist($artist);

        $lastFetch = new ArtistFetch($artist, new \DateTime('now'));
        $em->persist($lastFetch);

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
