<?php

namespace Tests\Functional\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider getPublicUrls
     */
    public function testPublicUrls($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertTrue(
            $client->getResponse()->isSuccessful(),
            sprintf('The %s public URL loads correctly.', $url)
        );
    }

    public function getPublicUrls()
    {
        yield ['/'];
    }

    /**
     * @dataProvider getSecureUrls
     */
//    public function testSecureUrls($url)
//    {
//        $client = self::createClient();
//        $client->request('GET', $url);
//
//        $this->assertTrue($client->getResponse()->isRedirect());
//
//        $this->assertEquals(
//            'http://localhost/login',
//            $client->getResponse()->getTargetUrl(),
//            sprintf('The %s secure URL redirects to the login form.', $url)
//        );
//    }

//    public function getSecureUrls()
//    {
//        yield ['/admin'];
//    }
}
