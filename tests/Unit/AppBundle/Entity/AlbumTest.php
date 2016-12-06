<?php
namespace Tests\Unit\AppBundle\Entity;

use AppBundle\Entity\Album;
use AppBundle\Entity\Artist;

class AlbumTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Album
     */
    private $album;

    /**
     * Set Album.
     */
    protected function setUp()
    {
        $this->album = new Album(new Artist());
    }

    /**
     * Test album types validation.
     * @dataProvider getAlbumTypes
     * @param array $types
     * @param bool $throw
     */
    public function testAlbumTypeValidation(array $types, bool $throw)
    {
        if ($throw) {
            $this->expectException(\InvalidArgumentException::class);
        }
        $this->album->setTypes($types);

        $this->assertEquals($types, $this->album->getTypes());
    }

    public function getAlbumTypes()
    {
        yield [['demo'], false];
        yield [['album', 'single', 'ep'], false];
        yield [['invalid'], true];
        yield [['demo', 'invalid'], true];
    }

    /**
     * Test album types are saving in lower case.
     */
    public function testAlbumInLowerCase()
    {
        $actual = ['DemO', 'LIVE'];
        $expected = ['demo', 'live'];
        $this->album->setTypes($actual);

        $this->assertEquals($expected, $this->album->getTypes());
    }
}
