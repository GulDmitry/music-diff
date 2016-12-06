<?php

namespace Tests\Functional\RestMusicDiffBundle\Controller;

use AppBundle\Entity\Album;
use AppBundle\Entity\Artist;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArtistControllerTest extends WebTestCase
{
    /**
     * @var Artist
     */
    private $artist;

    /**
     * @var Album[]
     */
    private $albums = [];

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        self::bootKernel();

        $em = static::$kernel->getContainer()->get('doctrine')->getManager();

        $this->artist = (new Artist())->setName('Artist');

        $this->albums[] = $album1 = (new Album($this->artist))->setName('Album1');
        $this->albums[] = $album2 = (new Album($this->artist))->setName('Album2');

        $em->persist($this->artist);
        $em->persist($album1);
        $em->persist($album2);

        $em->flush();
    }

    /**
     * @inheritdoc
     */
    protected function tearDown()
    {
        $em = static::$kernel->getContainer()->get('doctrine')->getManager();
        $em->clear();

        foreach ($this->albums as $album) {
            $em->remove($em->merge($album));
        }
        $em->remove($em->merge($this->artist));

        $em->flush();
        $em->close();

        parent::tearDown();
    }

    /**
     *
     */
    public function testGetArtistAlbumsAction()
    {
        $client = static::createClient();

        $client->request(
            'GET',
            "api/artists/{$this->artist->getName()}/albums",
            [],
            [],
            [
                'HTTP_X-Accept-Version' => 'v1',
                'accept' => 'application/json'
            ]
        );

        $body = json_decode($client->getResponse()->getContent());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals($this->artist->getId(), $body->id);
        $this->assertEquals($this->artist->getName(), $body->name);
        $this->assertEquals($this->albums[0]->getId(), $body->albums[0]->id);
        $this->assertEquals($this->albums[0]->getName(), $body->albums[0]->name);
        $this->assertEquals($this->albums[1]->getId(), $body->albums[1]->id);
        $this->assertEquals($this->albums[1]->getName(), $body->albums[1]->name);
    }
}
