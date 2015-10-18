<?php

namespace siestaphp\tests\functional;

use Codeception\Util\Debug;
use siestaphp\datamodel\attribute\AttributeSource;
use siestaphp\datamodel\DataModelContainer;
use siestaphp\datamodel\entity\EntitySource;
use siestaphp\generator\ValidationLogger;
use siestaphp\migrator\DatabaseMigrator;
use siestaphp\migrator\Migrator;
use siestaphp\util\File;
use siestaphp\util\Util;
use siestaphp\xmlreader\XMLReader;

/**
 * Class MigrationATest
 * @package siestaphp\tests\functional
 */
class MigrationATest extends SiestaTester
{

    const DATABASE_NAME = "MIGRATIONA_TEST";

    const ASSET_PATH = "/migrationa";

    const SRC_XML = "/MigrationA.test.xml";

    const TABLE = "CREATE TABLE IF NOT EXISTS `ARTIST`(`id` INT NULL,`name` VARCHAR(100) NULL,`city` VARCHAR(100) NOT NULL,`zip` INT NULL ,PRIMARY KEY (`id`))";

    protected function setUp()
    {
        $this->connectAndInstall(self::DATABASE_NAME);

        $this->connection->query(self::TABLE);

        $this->assertNoValidationErrors();

    }

    protected function tearDown()
    {
        $this->dropDatabase();

    }

    public function testMigration()
    {

        // read model
        $this->logger = new CodeceptionLogger();
        $dmc = new DataModelContainer(new ValidationLogger($this->logger));
        $xmlReader = new XMLReader(new File(__DIR__ . self::ASSET_PATH . self::SRC_XML));
        $dmc->addEntitySourceList($xmlReader->getEntitySourceList());
        $dmc->updateModel();
        $dmc->validate();
        $artistEntiy = $dmc->getEntityByClassname("Artist");
        $this->assertNotNull($artistEntiy);

        // migrate the current database
        $migrator = new Migrator($dmc, $this->connection, $this->logger);
        $migrator->migrate();

        // read meta data from database and find ArtistEntity
        $entitySourceList = $this->connection->getEntitySourceList();

        $artistDatabaseEntity = null;
        foreach ($entitySourceList as $entity) {
            if ($entity->getClassName() === "Artist") {
                $artistDatabaseEntity = $entity;
            }
        }
        $this->assertNotNull($artistDatabaseEntity);

        // compare database and model entity
        $this->assertSame($artistEntiy->getClassName(), $artistDatabaseEntity->getClassName());
        $this->assertSame($artistEntiy->getTable(), $artistDatabaseEntity->getTable());

        // compare attribtues
        $this->assertSame(sizeof($artistEntiy->getAttributeSourceList()), sizeof($artistDatabaseEntity->getAttributeSourceList()));

        foreach ($artistEntiy->getAttributeSourceList() as $attribute) {
            $databaseAttribute = $this->getAttributeByDatabaseName($artistDatabaseEntity->getAttributeSourceList(), $attribute->getDatabaseName());
            $this->assertNotNull($databaseAttribute);

            $this->assertSame($databaseAttribute->isRequired(), $attribute->isRequired());
            $this->assertSame($databaseAttribute->isPrimaryKey(), $attribute->isPrimaryKey());
            $this->assertSame($databaseAttribute->getDatabaseType(), $attribute->getDatabaseType());
        }

    }

    /**
     * @param AttributeSource[] $attributeList
     * @param $databaseName
     *
     * @return AttributeSource
     */
    private function getAttributeByDatabaseName(array $attributeList, $databaseName)
    {
        foreach ($attributeList as $attribute) {
            if ($attribute->getDatabaseName() === $databaseName) {
                return $attribute;
            }
        }
        return null;
    }

}