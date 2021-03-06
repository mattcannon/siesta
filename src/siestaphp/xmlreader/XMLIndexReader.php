<?php

namespace siestaphp\xmlreader;

use siestaphp\datamodel\DatabaseColumn;
use siestaphp\datamodel\index\IndexPartSource;
use siestaphp\datamodel\index\IndexSource;
use siestaphp\naming\XMLIndex;
use siestaphp\naming\XMLIndexPart;

/**
 * Class XMLIndexReader
 * @package siestaphp\xmlreader
 */
class XMLIndexReader extends XMLAccess implements IndexSource
{

    /**
     * @var IndexPartSource[]
     */
    protected $indexPartList;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getAttribute(XMLIndex::ATTRIBUTE_NAME);
    }

    /**
     * @return bool
     */
    public function isUnique()
    {
        return $this->getAttributeAsBool(XMLIndex::ATTRIBUTE_UNIQUE);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return strtoupper($this->getAttribute(XMLIndex::ATTRIBUTE_TYPE));
    }

    /**
     * @return IndexPartSource[]
     */
    public function getIndexPartSourceList()
    {
        if ($this->indexPartList === null) {
            $this->readIndexPartList();
        }
        return $this->indexPartList;

    }

    /**
     * @return void
     */
    private function readIndexPartList()
    {
        $this->indexPartList = array();
        $indexPartXMLList = $this->getXMLChildElementListByName(XMLIndexPart::ELEMENT_INDEX_PART_NAME);

        foreach ($indexPartXMLList as $indexPartXML) {
            $indexPart = new XMLIndexPartReader();
            $indexPart->setSource($indexPartXML);
            $this->indexPartList[] = $indexPart;
        }
    }

    /**
     * @return DatabaseColumn[]
     */
    public function getReferencedColumnList()
    {
        return array();
    }

}