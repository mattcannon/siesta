<?php

namespace siestaphp\tests\functional;

/**
 * Class MultiPKTest
 */
class IndexTest extends SiestaTester
{

    const ASSET_PATH = "/index";

    const SRC_XML = "/Index.test.xml";

    protected function setUp()
    {
        $this->connectAndInstall();

        $this->generateEntityFile(self::ASSET_PATH, self::SRC_XML);

        $this->assertNoValidationErrors();

    }

    protected function tearDown()
    {
        $this->dropDatabase();

    }

    public function testIndexList()
    {

    }

}