<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 * Generated from funding/xml/schema/CRM/Funding/FundingCaseContactRelation.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:3148e278fa59b155a7ed4b1e80b05613)
 */
use CRM_Funding_ExtensionUtil as E;

/**
 * Database access object for the FundingCaseContactRelation entity.
 */
class CRM_Funding_DAO_FundingCaseContactRelation extends CRM_Core_DAO {
  const EXT = E::LONG_NAME;
  const TABLE_ADDED = '';

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_funding_case_contact_relation';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  public static $_log = TRUE;

  /**
   * Unique FundingCaseContactRelation ID
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $id;

  /**
   * FK to FundingCase
   *
   * @var int|string
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $funding_case_id;

  /**
   * Table referenced by ID in `entity_id
   *
   * @var string
   *   (SQL type: varchar(64))
   *   Note that values will be retrieved from the database as a string.
   */
  public $entity_table;

  /**
   * ID of entity in `entity_table`
   *
   * @var int|string
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $entity_id;

  /**
   * FK to FundingCaseContactRelation
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $parent_id;

  /**
   * Permissions as JSON array
   *
   * @var string|null
   *   (SQL type: varchar(512))
   *   Note that values will be retrieved from the database as a string.
   */
  public $permissions;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'civicrm_funding_case_contact_relation';
    parent::__construct();
  }

  /**
   * Returns localized title of this entity.
   *
   * @param bool $plural
   *   Whether to return the plural version of the title.
   */
  public static function getEntityTitle($plural = FALSE) {
    return $plural ? E::ts('Funding Case Contact Relations') : E::ts('Funding Case Contact Relation');
  }

  /**
   * Returns foreign keys and entity references.
   *
   * @return array
   *   [CRM_Core_Reference_Interface]
   */
  public static function getReferenceColumns() {
    if (!isset(Civi::$statics[__CLASS__]['links'])) {
      Civi::$statics[__CLASS__]['links'] = static::createReferenceColumns(__CLASS__);
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'funding_case_id', 'civicrm_funding_case', 'id');
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'parent_id', 'civicrm_funding_case_contact_relation', 'id');
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'links_callback', Civi::$statics[__CLASS__]['links']);
    }
    return Civi::$statics[__CLASS__]['links'];
  }

  /**
   * Returns all the column names of this table
   *
   * @return array
   */
  public static function &fields() {
    if (!isset(Civi::$statics[__CLASS__]['fields'])) {
      Civi::$statics[__CLASS__]['fields'] = [
        'id' => [
          'name' => 'id',
          'type' => CRM_Utils_Type::T_INT,
          'description' => E::ts('Unique FundingCaseContactRelation ID'),
          'required' => TRUE,
          'where' => 'civicrm_funding_case_contact_relation.id',
          'table_name' => 'civicrm_funding_case_contact_relation',
          'entity' => 'FundingCaseContactRelation',
          'bao' => 'CRM_Funding_DAO_FundingCaseContactRelation',
          'localizable' => 0,
          'html' => [
            'type' => 'Number',
          ],
          'readonly' => TRUE,
          'add' => NULL,
        ],
        'funding_case_id' => [
          'name' => 'funding_case_id',
          'type' => CRM_Utils_Type::T_INT,
          'description' => E::ts('FK to FundingCase'),
          'required' => TRUE,
          'where' => 'civicrm_funding_case_contact_relation.funding_case_id',
          'table_name' => 'civicrm_funding_case_contact_relation',
          'entity' => 'FundingCaseContactRelation',
          'bao' => 'CRM_Funding_DAO_FundingCaseContactRelation',
          'localizable' => 0,
          'FKClassName' => 'CRM_Funding_DAO_FundingCase',
          'html' => [
            'type' => 'EntityRef',
          ],
          'add' => NULL,
        ],
        'entity_table' => [
          'name' => 'entity_table',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Entity Table'),
          'description' => E::ts('Table referenced by ID in `entity_id'),
          'required' => TRUE,
          'maxlength' => 64,
          'size' => CRM_Utils_Type::BIG,
          'where' => 'civicrm_funding_case_contact_relation.entity_table',
          'table_name' => 'civicrm_funding_case_contact_relation',
          'entity' => 'FundingCaseContactRelation',
          'bao' => 'CRM_Funding_DAO_FundingCaseContactRelation',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
          'add' => NULL,
        ],
        'entity_id' => [
          'name' => 'entity_id',
          'type' => CRM_Utils_Type::T_INT,
          'description' => E::ts('ID of entity in `entity_table`'),
          'required' => TRUE,
          'where' => 'civicrm_funding_case_contact_relation.entity_id',
          'table_name' => 'civicrm_funding_case_contact_relation',
          'entity' => 'FundingCaseContactRelation',
          'bao' => 'CRM_Funding_DAO_FundingCaseContactRelation',
          'localizable' => 0,
          'html' => [
            'type' => 'Number',
          ],
          'add' => NULL,
        ],
        'parent_id' => [
          'name' => 'parent_id',
          'type' => CRM_Utils_Type::T_INT,
          'description' => E::ts('FK to FundingCaseContactRelation'),
          'where' => 'civicrm_funding_case_contact_relation.parent_id',
          'table_name' => 'civicrm_funding_case_contact_relation',
          'entity' => 'FundingCaseContactRelation',
          'bao' => 'CRM_Funding_DAO_FundingCaseContactRelation',
          'localizable' => 0,
          'FKClassName' => 'CRM_Funding_DAO_FundingCaseContactRelation',
          'html' => [
            'type' => 'EntityRef',
          ],
          'add' => NULL,
        ],
        'permissions' => [
          'name' => 'permissions',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Permissions'),
          'description' => E::ts('Permissions as JSON array'),
          'maxlength' => 512,
          'size' => CRM_Utils_Type::HUGE,
          'where' => 'civicrm_funding_case_contact_relation.permissions',
          'table_name' => 'civicrm_funding_case_contact_relation',
          'entity' => 'FundingCaseContactRelation',
          'bao' => 'CRM_Funding_DAO_FundingCaseContactRelation',
          'localizable' => 0,
          'serialize' => self::SERIALIZE_JSON,
          'html' => [
            'type' => 'Text',
          ],
          'add' => NULL,
        ],
      ];
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'fields_callback', Civi::$statics[__CLASS__]['fields']);
    }
    return Civi::$statics[__CLASS__]['fields'];
  }

  /**
   * Return a mapping from field-name to the corresponding key (as used in fields()).
   *
   * @return array
   *   Array(string $name => string $uniqueName).
   */
  public static function &fieldKeys() {
    if (!isset(Civi::$statics[__CLASS__]['fieldKeys'])) {
      Civi::$statics[__CLASS__]['fieldKeys'] = array_flip(CRM_Utils_Array::collect('name', self::fields()));
    }
    return Civi::$statics[__CLASS__]['fieldKeys'];
  }

  /**
   * Returns the names of this table
   *
   * @return string
   */
  public static function getTableName() {
    return self::$_tableName;
  }

  /**
   * Returns if this table needs to be logged
   *
   * @return bool
   */
  public function getLog() {
    return self::$_log;
  }

  /**
   * Returns the list of fields that can be imported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &import($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'funding_case_contact_relation', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of fields that can be exported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &export($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'funding_case_contact_relation', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of indices
   *
   * @param bool $localize
   *
   * @return array
   */
  public static function indices($localize = TRUE) {
    $indices = [];
    return ($localize && !empty($indices)) ? CRM_Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }

}
