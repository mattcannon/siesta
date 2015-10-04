<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 29.09.15
 * Time: 10:28
 */

namespace siestaphp\datamodel\index;

use siestaphp\datamodel\DatabaseColumn;
use siestaphp\datamodel\DataModelContainer;
use siestaphp\datamodel\entity\Entity;
use siestaphp\datamodel\entity\EntitySource;
use siestaphp\datamodel\Processable;
use siestaphp\generator\GeneratorLog;
use siestaphp\naming\XMLIndexPart;

/**
 * Class IndexPart
 * @package siestaphp\datamodel\index
 */
class IndexPart implements Processable, IndexPartSource, IndexPartDatabaseSource
{

    /**
     * @var EntitySource
     */
    protected $entity;

    /**
     * @var Index
     */
    protected $index;

    /**
     * @var IndexPartSource
     */
    protected $indexPartSource;

    /**
     * @var DatabaseColumn[]
     */
    protected $databaseColumnList;

    /**
     * @param Entity $entity
     * @param Index $index
     * @param IndexPartSource $indexPartSource
     */
    public function __construct(Entity $entity, Index $index, IndexPartSource $indexPartSource)
    {
        $this->entity = $entity;
        $this->index = $index;
        $this->indexPartSource = $indexPartSource;
        $this->databaseColumnList = array();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->indexPartSource->getName();
    }

    /**
     * @return string
     */
    public function getSortOrder()
    {
        return $this->indexPartSource->getSortOrder();
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return $this->indexPartSource->getLength();
    }

    /**
     * @return DatabaseColumn[]
     */
    public function getIndexColumnList() {
        return $this->databaseColumnList;
    }


    /**
     * @param DataModelContainer $container
     */
    public function updateModel(DataModelContainer $container)
    {
        $this->checkIfIndexPartIsForAttribute();
        $this->checkIfIndexPartIsForReference();


    }

    /**
     * checks if the index part refers an attribute of the entity
     */
    private function checkIfIndexPartIsForAttribute() {
        $attribute = $this->entity->getAttributeByName($this->getName());
        if (!$attribute) {
            return;
        }
        $this->databaseColumnList[] = $attribute;
    }

    /**
     * checks if the index part refers a reference of the entity
     */
    private function checkIfIndexPartIsForReference() {

        $reference = $this->entity->getReferenceByName($this->getName());
        if (!$reference) {
            return;
        }

        // a reference might have several referenced columns
        $referencedColumns = $reference->getReferenceColumnList();
        if (!$referencedColumns) {
            return;
        }

        $this->databaseColumnList = array_merge($this->databaseColumnList, $referencedColumns);
    }

    /**
     * @param GeneratorLog $log
     */
    public function validate(GeneratorLog $log)
    {
        $log->errorIfAttributeNotSet($this->getName(), XMLIndexPart::ATTRIBUTE_NAME, XMLIndexPart::ELEMENT_INDEX_PART_NAME);

        if (sizeof($this->databaseColumnList) === 0) {
            $log->error("IndexPart " . $this->getName() . " from index " . $this->index->getName() . " does not refer an existing attribute or reference");
        }
    }


}