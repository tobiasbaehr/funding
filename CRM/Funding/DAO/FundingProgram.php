<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 * Generated from funding/xml/schema/CRM/Funding/FundingProgram.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:7ad8bb08eb2ade299e84cb68cbe259ee)
 */
use CRM_Funding_ExtensionUtil as E;

/**
 * Database access object for the FundingProgram entity.
 */
class CRM_Funding_DAO_FundingProgram extends CRM_Core_DAO {
  const EXT = E::LONG_NAME;
  const TABLE_ADDED = '';

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_funding_program';

  /**
   * Field to show when displaying a record.
   *
   * @var string
   */
  public static $_labelField = 'title';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  public static $_log = TRUE;

  /**
   * Unique FundingProgram ID
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
   * Used in application process identifiers
   *
   * @var string
   *   (SQL type: varchar(20))
   *   Note that values will be retrieved from the database as a string.
   */
  public $abbreviation;

  /**
   * @var string
   *   (SQL type: date)
   *   Note that values will be retrieved from the database as a string.
   */
  public $start_date;

  /**
   * @var string
   *   (SQL type: date)
   *   Note that values will be retrieved from the database as a string.
   */
  public $end_date;

  /**
   * @var string
   *   (SQL type: date)
   *   Note that values will be retrieved from the database as a string.
   */
  public $requests_start_date;

  /**
   * @var string
   *   (SQL type: date)
   *   Note that values will be retrieved from the database as a string.
   */
  public $requests_end_date;

  /**
   * @var string
   *   (SQL type: varchar(10))
   *   Note that values will be retrieved from the database as a string.
   */
  public $currency;

  /**
   * @var string
   *   (SQL type: decimal(10,2))
   *   Note that values will be retrieved from the database as a string.
   */
  public $budget;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'civicrm_funding_program';
    parent::__construct();
  }

  /**
   * Returns localized title of this entity.
   *
   * @param bool $plural
   *   Whether to return the plural version of the title.
   */
  public static function getEntityTitle($plural = FALSE) {
    return $plural ? E::ts('Funding Programs') : E::ts('Funding Program');
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
          'title' => E::ts('ID'),
          'description' => E::ts('Unique FundingProgram ID'),
          'required' => TRUE,
          'where' => 'civicrm_funding_program.id',
          'table_name' => 'civicrm_funding_program',
          'entity' => 'FundingProgram',
          'bao' => 'CRM_Funding_DAO_FundingProgram',
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
          'where' => 'civicrm_funding_program.title',
          'table_name' => 'civicrm_funding_program',
          'entity' => 'FundingProgram',
          'bao' => 'CRM_Funding_DAO_FundingProgram',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
          'add' => NULL,
        ],
        'abbreviation' => [
          'name' => 'abbreviation',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Abbreviation'),
          'description' => E::ts('Used in application process identifiers'),
          'required' => TRUE,
          'maxlength' => 20,
          'size' => CRM_Utils_Type::MEDIUM,
          'where' => 'civicrm_funding_program.abbreviation',
          'dataPattern' => '/^[\p{L}\p{N}\p{P}]+$/u',
          'table_name' => 'civicrm_funding_program',
          'entity' => 'FundingProgram',
          'bao' => 'CRM_Funding_DAO_FundingProgram',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
          'add' => NULL,
        ],
        'start_date' => [
          'name' => 'start_date',
          'type' => CRM_Utils_Type::T_DATE,
          'title' => E::ts('Start Date'),
          'required' => TRUE,
          'where' => 'civicrm_funding_program.start_date',
          'table_name' => 'civicrm_funding_program',
          'entity' => 'FundingProgram',
          'bao' => 'CRM_Funding_DAO_FundingProgram',
          'localizable' => 0,
          'html' => [
            'type' => 'Select Date',
          ],
          'add' => NULL,
        ],
        'end_date' => [
          'name' => 'end_date',
          'type' => CRM_Utils_Type::T_DATE,
          'title' => E::ts('End Date'),
          'required' => TRUE,
          'where' => 'civicrm_funding_program.end_date',
          'table_name' => 'civicrm_funding_program',
          'entity' => 'FundingProgram',
          'bao' => 'CRM_Funding_DAO_FundingProgram',
          'localizable' => 0,
          'html' => [
            'type' => 'Select Date',
          ],
          'add' => NULL,
        ],
        'requests_start_date' => [
          'name' => 'requests_start_date',
          'type' => CRM_Utils_Type::T_DATE,
          'title' => E::ts('Requests Start Date'),
          'required' => TRUE,
          'where' => 'civicrm_funding_program.requests_start_date',
          'table_name' => 'civicrm_funding_program',
          'entity' => 'FundingProgram',
          'bao' => 'CRM_Funding_DAO_FundingProgram',
          'localizable' => 0,
          'html' => [
            'type' => 'Select Date',
          ],
          'add' => NULL,
        ],
        'requests_end_date' => [
          'name' => 'requests_end_date',
          'type' => CRM_Utils_Type::T_DATE,
          'title' => E::ts('Requests End Date'),
          'required' => TRUE,
          'where' => 'civicrm_funding_program.requests_end_date',
          'table_name' => 'civicrm_funding_program',
          'entity' => 'FundingProgram',
          'bao' => 'CRM_Funding_DAO_FundingProgram',
          'localizable' => 0,
          'html' => [
            'type' => 'Select Date',
          ],
          'add' => NULL,
        ],
        'currency' => [
          'name' => 'currency',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Currency'),
          'required' => TRUE,
          'maxlength' => 10,
          'size' => CRM_Utils_Type::TWELVE,
          'where' => 'civicrm_funding_program.currency',
          'table_name' => 'civicrm_funding_program',
          'entity' => 'FundingProgram',
          'bao' => 'CRM_Funding_DAO_FundingProgram',
          'localizable' => 0,
          'html' => [
            'type' => 'Select',
          ],
          'pseudoconstant' => [
            'table' => 'civicrm_currency',
            'keyColumn' => 'name',
            'labelColumn' => 'full_name',
            'nameColumn' => 'name',
            'abbrColumn' => 'symbol',
          ],
          'add' => NULL,
        ],
        'budget' => [
          'name' => 'budget',
          'type' => CRM_Utils_Type::T_MONEY,
          'title' => E::ts('Budget'),
          'required' => FALSE,
          'where' => 'civicrm_funding_program.budget',
          'dataPattern' => '/^\d{1,10}(\.\d{2})?$/',
          'table_name' => 'civicrm_funding_program',
          'entity' => 'FundingProgram',
          'bao' => 'CRM_Funding_DAO_FundingProgram',
          'localizable' => 0,
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
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'funding_program', $prefix, []);
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
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'funding_program', $prefix, []);
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
        'sig' => 'civicrm_funding_program::1::title',
      ],
      'index_abbreviation' => [
        'name' => 'index_abbreviation',
        'field' => [
          0 => 'abbreviation',
        ],
        'localizable' => FALSE,
        'unique' => TRUE,
        'sig' => 'civicrm_funding_program::1::abbreviation',
      ],
    ];
    return ($localize && !empty($indices)) ? CRM_Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }

}
