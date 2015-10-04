<?php

use \transfomertest\LabelArtistXML;

require_once 'transformer.test/LabelArtistXML.php';

/**
 * Class TransformerTest
 */
class TransformerTest extends \PHPUnit_Framework_TestCase
{

    const ASSET_PATH = "/transformer.test";

    /**
     * @return \siestaphp\datamodel\entity\Entity
     */
    private function loadEntitySource()
    {
        // read file
        $file = new \siestaphp\util\File(__DIR__ . self::ASSET_PATH . "/LabelArtist.test.xml");
        $xmlReader = new \siestaphp\xmlreader\XMLReader($file);

        // get entities
        $entitySourceList = $xmlReader->getEntitySourceList();

        // create datamodel
        $dataModelContainer = new \siestaphp\datamodel\DataModelContainer(null);
        $dataModelContainer->addEntitySourceList($entitySourceList);
        $dataModelContainer->updateModel();

        // get artist entity
        $entitySource = $dataModelContainer->getEntityDetails("ArtistEntity");

        // check that artist entity was found
        $this->assertNotNull($entitySource, "ArtistEntity not found");

        return $entitySource;

    }

    public function testEntity()
    {
        $entity = $this->loadEntitySource();
        $definition = LabelArtistXML::getEntityTransformerDefinition();

        $this->assertSame($entity->getClassName(), $definition["name"], "name is not correct");
        $this->assertSame($entity->getClassNamespace(), $definition["namespace"], "namespace is not correct");
        $this->assertSame($entity->getConstructorClass(), $definition["constructClass"], "constructClass is not correct");
        $this->assertSame($entity->getConstructorNamespace(), $definition["constructNamespace"], "constructNamespace is not correct");
        $this->assertSame($entity->getTable(), $definition["table"], "table is not correct");
        $this->assertSame($entity->isDelimit(), $definition["delimit"], "delimit is not correct");
        $this->assertSame($entity->getTargetPath(), $definition["targetPath"], "targetPath is not correct");

        $this->assertSame($entity->isDateTimeUsed(), $definition["dateTimeInUse"], "dateTimeInUse is not correct");
        $this->assertSame($entity->hasReferences(), $definition["hasReferences"], "hasReferences is not correct");
        $this->assertSame($entity->hasAttributes(), $definition["hasAttributes"], "hasAttributes is not correct");
        $this->assertSame($entity->getFindByPKSignature(), $definition["findByPKSignature"], "findByPKSignature is not correct");
        $this->assertSame($entity->getSPCallSignature(), $definition["storedProcedureCallSignature"], "storedProcedureCallSignature is not correct");
    }

    public function testAttributeList()
    {
        $entity = $this->loadEntitySource();
        foreach ($entity->getAttributeSourceList() as $attributeSource) {
            $this->testAttribute($attributeSource);
        }
    }

    /**
     * tests an attribute
     *
     * @param \siestaphp\datamodel\attribute\AttributeTransformerSource $ats
     */
    private function testAttribute(\siestaphp\datamodel\attribute\AttributeTransformerSource $ats)
    {
        // get name
        $attributeName = $ats->getName();

        // get definition
        $definitionList = LabelArtistXML::getAttributeTransformerDefinition();
        $definition = \siestaphp\util\Util::getFromIndex($definitionList, $attributeName);
        $this->assertNotNull($definition, "Attribute " . $attributeName . " not in definition list");

        // check attribute values
        $this->assertSame($ats->getPHPType(), $definition["type"], "Attribute $attributeName type is not correct : " . $ats->getPHPType() . " vs " . $definition["type"]);
        $this->assertSame($ats->getDatabaseName(), $definition["dbName"], "Attribute $attributeName dbName is not correct");
        $this->assertSame($ats->getDatabaseType(), $definition["dbType"], "Attribute $attributeName dbType is not correct");
        $this->assertSame($ats->isPrimaryKey(), $definition["primaryKey"], "Attribute $attributeName primaryKey is not correct");
        $this->assertSame($ats->isRequired(), $definition["required"], "Attribute $attributeName required is not correct");
        $this->assertSame($ats->getDefaultValue(), $definition["defaultValue"], "Attribute $attributeName defaultValue is not correct");
        $this->assertSame($ats->getAutoValue(), $definition["autoValue"], "Attribute $attributeName autoValue is not correct");
        $this->assertSame($ats->getLength(), $definition["length"], "Attribute $attributeName length is not correct");

        // derived data
        $this->assertSame($ats->getMethodName(), $definition["methodName"], "Attribute $attributeName methodName is not correct");

    }

    public function testReferenceList()
    {
        $entity = $this->loadEntitySource();
        foreach ($entity->getReferenceSourceList() as $reference) {
            $this->testReference($reference);
        }
    }

    /**
     * checks a reference
     *
     * @param \siestaphp\datamodel\reference\ReferenceTransformerSource $referenceSource
     */
    private function testReference(\siestaphp\datamodel\reference\ReferenceTransformerSource $referenceSource)
    {

        // get name
        $referenceName = $referenceSource->getName();

        // get definition
        $definitionList = LabelArtistXML::getReferenceTransformerDefinition();
        $definition = \siestaphp\util\Util::getFromIndex($definitionList, $referenceName);

        // check that reference exists
        $this->assertNotNull($definition, "Reference " . $referenceName . " not in definition list");

        // check reference values
        $this->assertSame($referenceSource->getForeignClass(), $definition["foreignClass"], "Reference $referenceName foreignClass is not correct");
        $this->assertSame($referenceSource->isRequired(), $definition["required"], "Reference $referenceName required is not correct");
        $this->assertSame($referenceSource->getOnDelete(), $definition["onDelete"], "Reference $referenceName onDelete is not correct");
        $this->assertSame($referenceSource->getOnUpdate(), $definition["onUpdate"], "Reference $referenceName onUpdate is not correct");
        $this->assertSame($referenceSource->getReferencedConstructClass(), $definition["foreignConstructClass"], "Reference $referenceName foreignConstructClass is not correct");
        $this->assertSame($referenceSource->getStoredProcedureFinderName(), $definition["storedProcedureFinderName"], "Reference $referenceName storedProcedureFinderName is not correct");
        $this->assertSame($referenceSource->getRelationName(), $definition["relationName"], "Reference $referenceName storedProcedureFinderName is not correct");
        $this->assertSame($referenceSource->isReferenceCreatorNeeded(), true, "Reference $referenceName isReferenceCreator is not correct");

        // iterate referenced columns
        foreach ($referenceSource->getReferenceColumnList() as $column) {
            $this->testReferencedColunm($column, $definition["columnList"]);
        }
    }

    /**
     * tests a referenced column
     *
     * @param \siestaphp\datamodel\reference\ReferencedColumnSource $column
     * @param array $data
     */
    private function testReferencedColunm(\siestaphp\datamodel\reference\ReferencedColumnSource $column, array $data)
    {
        // get name
        $columnName = $column->getName();

        // get data
        $definition = \siestaphp\util\Util::getFromIndex($data, $columnName);

        // check that data exists
        $this->assertNotNull($definition, "Referenced Column " . $columnName . " not in definition list");

        // check values are right
        $this->assertSame($column->getPHPType(), $definition["type"], "Referenced Column $columnName type is not correct");
        $this->assertSame($column->getMethodName(), $definition["methodName"], "Referenced Column $columnName methodName is not correct");
        $this->assertSame($column->getDatabaseName(), $definition["databaseName"], "Referenced Column $columnName databaseName is not correct");

    }

    public function testCollectorList()
    {
        $entity = $this->loadEntitySource();
        foreach ($entity->getCollectorSourceList() as $collector) {
            $this->testCollector($collector);
        }
    }

    /**
     * @param \siestaphp\datamodel\collector\CollectorTransformerSource $collectorSource
     */
    private function testCollector(\siestaphp\datamodel\collector\CollectorTransformerSource $collectorSource)
    {
        // get name
        $name = $collectorSource->getName();

        // find definition
        $definitionList = LabelArtistXML::getCollectorTransformerDefinition();
        $definition = \siestaphp\util\Util::getFromIndex($definitionList, $name);
        $this->assertNotNull($definition, "Collector " . $name . " not in definition list");

        $this->assertSame($collectorSource->getReferenceName(), $definition["referenceName"]);
        $this->assertSame($collectorSource->getForeignClass(), $definition["foreignClass"]);
        $this->assertSame($collectorSource->getType(), $definition["type"]);
        $this->assertSame($collectorSource->getMethodName(), $definition["methodName"]);

    }

    private function testIndexList()
    {

        $entity = $this->loadEntitySource();

        $this->assertSame(sizeof($entity->getIndexSourceList()), 2, "Not 2 indexes found");
        $this->assertSame(sizeof($entity->getIndexDatabaseSourceList()), 2, "Not 2 indexes found");

        foreach ($entity->getIndexDatabaseSourceList() as $indexDatabaseSource) {
            $this->testIndex($indexDatabaseSource);
        }
    }

    /**
     * @param \siestaphp\datamodel\index\IndexDatabaseSource $index
     */
    private function testIndex(\siestaphp\datamodel\index\IndexDatabaseSource $index)
    {
        $indexName = $index->getName();

        $definitionList = LabelArtistXML::getIndexDefinition();
        $definition = \siestaphp\util\Util::getFromIndex($definitionList, $indexName);
        $this->assertNotNull($definition, "Index " . $indexName . " not in definition list");

        $this->assertSame($index->isUnique(), $definition["unique"]);
        $this->assertSame($index->getType(), $definition["type"]);

        $this->assertSame(sizeof($index->getIndexDatabaseSourceList()), 2, " not to indexParts found");
        foreach ($index->getIndexDatabaseSourceList() as $indexPartSource) {
            $this->testIndexPart($indexName, $indexPartSource);
        }

    }

    /**
     * @param string $indexName
     * @param \siestaphp\datamodel\index\IndexPartDatabaseSource $indexPart
     */
    private function testIndexPart($indexName, \siestaphp\datamodel\index\IndexPartDatabaseSource $indexPart)
    {
        $indexPartName = $indexPart->getName();

        $definitionList = LabelArtistXML::getIndexPartDefinition();
        $indexPartListDefinition = \siestaphp\util\Util::getFromIndex($definitionList, $indexName);
        $this->assertNotNull($indexPartListDefinition, "Definition for " . $indexName . " not in definition list");

        $indexPartDefinition = \siestaphp\util\Util::getFromIndex($indexPartListDefinition, $indexPartName);
        $this->assertNotNull($indexPartDefinition, "Definition for " . $indexPartName . " not in definition list");

        $this->assertSame($indexPart->getSortOrder(), $indexPartDefinition["sortOrder"]);
        $this->assertSame($indexPart->getLength(), $indexPartDefinition["length"]);

        $indexPart->getIndexColumnList();

    }

    public function testStoredProcedureList()
    {
        $entity = $this->loadEntitySource();

        foreach ($entity as $sp) {
            $this->testStoredProcedure($sp);
        }
    }

    /**
     * @param \siestaphp\datamodel\storedprocedure\StoredProcedureSource $spSource
     */
    private function testStoredProcedure(\siestaphp\datamodel\storedprocedure\StoredProcedureSource $spSource)
    {
        $spDefinition = LabelArtistXML::getSPDefinition();
        $this->assertSame($spSource->getName(), $spDefinition["name"]);
        $this->assertSame($spSource->modifies(), $spDefinition["modifies"]);
        $this->assertSame($spSource->getSql(), $spDefinition["sql"]);
        $this->assertSame($spSource->getSql("mysql"), $spDefinition["mysql-sql"]);
        $this->assertSame($spSource->getResultType(), $spDefinition["resultType"]);

        $this->assertSame(sizeof($spSource->getParameterList()), 2, "not 2 parameters found");
        foreach ($spSource->getParameterList() as $param) {
            $this->testSPParameter($param);
        }

    }

    /**
     * @param \siestaphp\datamodel\storedprocedure\SPParameterSource $spParameterSource
     */
    private function testSPParameter(\siestaphp\datamodel\storedprocedure\SPParameterSource $spParameterSource)
    {
        $definitionList = LabelArtistXML::getSPParameterDefinition();

        // find definition
        $definition = \siestaphp\util\Util::getFromIndex($definitionList, $spParameterSource->getName());
        $this->assertNotNull($definition, "no definition for parameter " . $spParameterSource->getName() . " found");

        $this->assertSame($spParameterSource->getStoredProcedureName(), $definition["spName"]);
        $this->assertSame($spParameterSource->getDatabaseType(), $definition["dbType"]);
        $this->assertSame($spParameterSource->getPHPType(), $definition["type"]);
    }

}