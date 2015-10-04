<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 27.06.15
 * Time: 20:33
 */

namespace siestaphp\naming;

/**
 * Class XMLEntity
 * @package siestaphp\datamodel\xml
 */
class XMLEntity
{
    /* Element <entity/> with attributes */
    const ELEMENT_ENTITY_NAME = "entity";

    const ATTRIBUTE_CLASS_NAME = "name";

    const ATTRIBUTE_CLASS_NAMESPACE = "namespace";

    const ATTRIBUTE_CONSTRUCTOR_CLASS = "constructClass";

    const ATTRIBUTE_CONSTRUCTOR_NAMESPACE = "constructNamespace";

    const ATTRIBUTE_TABLE = "table";

    const ATTRIBUTE_DELIMIT = "delimit";

    const ATTRIBUTE_TARGET_PATH = "targetPath";

    /* derived data for generator purpose */

    const ATTRIBUTE_DATETIME_IN_USE = "dateTimeInUse";

    const ATTRIBUTE_HAS_REFERENCES = "hasReferences";

    const ATTRIBUTE_HAS_ATTRIBUTES = "hasAttributes";

    /* standard stored procedure names */
    const ELEMENT_STANDARD_STORED_PROCEDURES = "standardStoredProcedures";

    const ATTRIBUTE_SSP_FIND_BY_PK = "findByPrimaryKey";

    const ATTRIBUTE_SSP_DELETE_BY_PK = "deleteByPrimaryKey";

    const ATTRIBUTE_SSP_UPDATE = "update";

    const ATTRIBUTE_SSP_INSERT = "insert";

    /* use list */
    const ELEMENT_REFERENCE_USE_FQ_CLASS_NAME_LIST = "referenceUseList";

    const ELEMENT_REFERENCE_USE_FQ_CLASS_NAME = "referenceUse";

    const ATTRIBUTE_REFERENCE_USE_NAME = "use";

    /* primary key */
    const ATTRIBUTE_FIND_BY_PK_SIGNATURE = "findByPKSignature";

    const ATTRIBUTE_STORED_PROCEDURE_CALL_SIGNATURE = "storedProcedureCallSignature";

}