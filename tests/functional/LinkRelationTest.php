<?php

use gen\linkrelation\ArtistEntity;
use gen\linkrelation\LabelEntity;

/**
 * Class ReferenceTest
 */
class LinkRelation extends \SiestaTester
{

    const DATABASE_NAME = "LINK_RELATION_TEST";

    const ASSET_PATH = "/link.relation.test";

    const SRC_XML = "/Link.relation.test.xml";

    const TEST_JSON = "/link.relation.test/link.relation.test.json";

    protected function setUp()
    {
        $this->connectAndInstall(self::DATABASE_NAME);

        $this->generateEntityFile(self::ASSET_PATH, self::SRC_XML, array(
            "/gen/linkrelation/ArtistEntity.php",
            "/gen/linkrelation/LabelEntity.php"
        ));

    }

    protected function tearDown()
    {
        $this->dropDatabase();
    }

    public function testLinkRelations()
    {
        $jsonFile = new \siestaphp\util\File(__DIR__ . self::TEST_JSON);
        $jsonString = $jsonFile->getContents();

        $label = new LabelEntity();
        $label->fromJSON($jsonString);

        // id should be still null
        $this->assertNull($label->getId(), "ID must be null");

        // link the objects
        $label->linkRelations();

        // test the linking
        $this->assertNotNull($label->getId(), "ID must not be null");
        $this->assertSame($label->getTopSeller()->getId(), $label->getTopSellerId(), "1:1 not linked correctly");
        foreach ($label->getArtistList() as $artist) {
            $this->assertSame($label->getId(), $artist->getLabelId(), "Linking not succeeded");
        }
    }

}