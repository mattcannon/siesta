<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 27.09.15
 * Time: 11:41
 */

namespace siestaphp\xmlreader;


use siestaphp\datamodel\collector\CollectorSource;
use siestaphp\naming\XMLCollector;

/**
 * Class XMLCollectorReader
 * @package siestaphp\xmlreader
 */
class XMLCollectorReader extends XMLAccess implements CollectorSource
{
    /**
     * @return string
     */
    public function getName()
    {
       return $this->getAttribute(XMLCollector::ATTRIBUTE_NAME);
    }


    /**
     * @return string
     */
    public function getType()
    {
        return $this->getAttribute(XMLCollector::ATTRIBUTE_TYPE);
    }


    /**
     * @return string
     */
    public function getForeignClass()
    {
        return $this->getAttribute(XMLCollector::ATTRIBUTE_FOREIGN_CLASS);
    }

    /**
     * @return string
     */
    public function getReferenceName()
    {
        return $this->getAttribute(XMLCollector::ATTRIBUTE_REFERENCE_NAME);
    }

}