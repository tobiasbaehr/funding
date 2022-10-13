<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 * Generated from funding/xml/schema/CRM/Funding/FundingCaseType.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:f2d42e1b2af15912cb5e3f2680904105)
 */
use CRM_Funding_ExtensionUtil as E;

/**
 * Database access object for the FundingCaseType entity.
 */
class CRM_Funding_DAO_FundingCaseType extends CRM_Core_DAO {
  const EXT = E::LONG_NAME;
  const TABLE_ADDED = '';

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_funding_case_type';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  public static $_log = TRUE;

  /**
   * Unique FundingCaseType ID
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $id;

  /**
   * @var string
   *   (SQL type: varchar(255))
   *   Note that values will be retrieved from the database as a string.
   */
  public $title;

  /**
   * @var string
   *   (SQL type: varchar(255))
   *   Note that values will be retrieved from the database as a string.
   */
  public $name;

  /**
   * @var string
   *   (SQL type: text)
   *   Note that values will be retrieved from the database as a string.
   */
  public $properties;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'civicrm_funding_case_type';
    parent::__construct();
  }

  /**
   * Returns localized title of this entity.
   *
   * @param bool $plural
   *   Whether to return the plural version of the title.
   */
  public static function getEntityTitle($plural = FALSE) {
    return $plural ? E::ts('Funding Case Types') : E::ts('Funding Case Type');
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
          'description' => E::ts('Unique FundingCaseType ID'),
          'required' => TRUE,
          'where' => 'civicrm_funding_case_type.id',
          'table_name' => 'civicrm_funding_case_type',
          'entity' => 'FundingCaseType',
          'bao' => 'CRM_Funding_DAO_FundingCaseType',
          'localizable' => 0,
          'html' => [
            'type' => 'Number',
          ],
          'readonly' => TRUE,
          'add' => NULL,
        ],
        'title' => [
          'name' => 'title',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Title'),
          'required' => TRUE,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'where' => 'civicrm_funding_case_type.title',
          'table_name' => 'civicrm_funding_case_type',
          'entity' => 'FundingCaseType',
          'bao' => 'CRM_Funding_DAO_FundingCaseType',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
          'add' => NULL,
        ],
        'name' => [
          'name' => 'name',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Name'),
          'required' => TRUE,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'where' => 'civicrm_funding_case_type.name',
          'table_name' => 'civicrm_funding_case_type',
          'entity' => 'FundingCaseType',
          'bao' => 'CRM_Funding_DAO_FundingCaseType',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
          'add' => NULL,
        ],
        'properties' => [
          'name' => 'properties',
          'type' => CRM_Utils_Type::T_TEXT,
          'title' => E::ts('Properties'),
          'required' => FALSE,
          'where' => 'civicrm_funding_case_type.properties',
          'table_name' => 'civicrm_funding_case_type',
          'entity' => 'FundingCaseType',
          'bao' => 'CRM_Funding_DAO_FundingCaseType',
          'localizable' => 0,
          'serialize' => self::SERIALIZE_JSON,
          'html' => [
            'type' => 'TextArea',
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
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'funding_case_type', $prefix, []);
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
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'funding_case_type', $prefix, []);
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
    $indices = [
      'index_title' => [
        'name' => 'index_title',
        'field' => [
          0 => 'title',
        ],
        'localizable' => FALSE,
        'unique' => TRUE,
        'sig' => 'civicrm_funding_case_type::1::title',
      ],
    ];
    return ($localize && !empty($indices)) ? CRM_Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }

}
