<?php

use gen\collector1n\ArtistEntity;
use gen\collector1n\LabelEntity;

/**
 * Class ReferenceTest
 */
class SPTest extends \SiestaTester
{

    const DATABASE_NAME = "SP_TEST";

    const ASSET_PATH = "/sp.test";

    const SRC_XML = "/SP.test.xml";

    protected function setUp()
    {
        $this->connectAndInstall(self::DATABASE_NAME);

        $this->generateEntityFile(self::ASSET_PATH, self::SRC_XML, array(
            "/gen/sp/ArtistEntity.php",
            "/gen/sp/LabelEntity.php"
        ));

        $this->generateData();
    }

    protected function tearDown()
    {
        $this->dropDatabase();

    }

    private function generateData()
    {
        $kd = new \gen\sp\ArtistEntity();
        $kd->setName("Kruder & Dorfmeister");
        $kd->setCity("Vienna");
        $kd->save();

        $dk = new \gen\sp\ArtistEntity();
        $dk->setName("dZihan & Kamien");
        $dk->setCity("Vienna");
        $dk->save();

    }

    public function testSingleResultSP()
    {
        $firstArtist = \gen\sp\ArtistEntity::getFirstArtistByCity("Vienna");

        $this->assertNotNull($firstArtist);
        $this->assertInstanceOf("\gen\sp\ArtistEntity", $firstArtist, "Not instance of ArtistEntity");

    }

    public function testListResultSP()
    {
        $artistList = \gen\sp\ArtistEntity::getArtistByCity("Vienna");

        $this->assertSame(sizeof($artistList), 2, "not 2 artist found");

        foreach ($artistList as $artist) {
            $this->assertInstanceOf("\gen\sp\ArtistEntity", $artist, "Not instance of ArtistEntity");
        }
    }

    public function testResultSetSP()
    {
        $count = null;
        $countArtistResult = \gen\sp\ArtistEntity::countArtistInCity("Vienna");
        while ($countArtistResult->hasNext()) {
            $count = $countArtistResult->getIntegerValue("COUNT(ID)");
        }
        $countArtistResult->close();
        $this->assertSame($count, 2, "Count not right");
    }

}