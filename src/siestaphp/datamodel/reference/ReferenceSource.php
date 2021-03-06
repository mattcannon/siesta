<?php

namespace siestaphp\datamodel\reference;

/**
 * Class Reference
 * @package siestaphp\datamodel
 */
interface ReferenceSource
{

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getRelationName();

    /**
     * @return string
     */
    public function getForeignClass();

    /**
     * @return string
     */
    public function getForeignTable();

    /**
     * @return bool
     */
    public function isRequired();

    /**
     * @return string
     */
    public function getOnDelete();

    /**
     * @return string
     */
    public function getOnUpdate();

    /**
     * @return bool
     */
    public function isPrimaryKey();

    /**
     * @return string
     */
    public function getConstraintName();

    /**
     * @return MappingSource[]
     */
    public function getMappingSourceList();

    /**
     * @return ReferencedColumnSource[]
     */
    public function getReferencedColumnList();

}