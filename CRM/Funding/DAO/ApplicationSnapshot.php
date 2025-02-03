<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 * Generated from funding/xml/schema/CRM/Funding/FundingApplicationSnapshot.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:37dfdcd56d362345ef3424fc27219be4)
 */
use CRM_Funding_ExtensionUtil as E;

/**
 * Database access object for the ApplicationSnapshot entity.
 */
class CRM_Funding_DAO_ApplicationSnapshot extends CRM_Core_DAO {
  const EXT = E::LONG_NAME;
  const TABLE_ADDED = '';

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_funding_application_snapshot';

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
   * Unique FundingApplicationSnapshot ID
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $id;

  /**
   * FK to FundingApplicationProcess
   *
   * @var int|string
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $application_process_id;

  /**
   * @var string
   *   (SQL type: varchar(64))
   *   Note that values will be retrieved from the database as a string.
   */
  public $status;

  /**
   * @var string
   *   (SQL type: timestamp)
   *   Note that values will be retrieved from the database as a string.
   */
  public $creation_date;

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
  public $short_description;

  /**
   * @var string
   *   (SQL type: timestamp)
   *   Note that values will be retrieved from the database as a string.
   */
  public $start_date;

  /**
   * @var string
   *   (SQL type: timestamp)
   *   Note that values will be retrieved from the database as a string.
   */
  public $end_date;

  /**
   * @var string
   *   (SQL type: text)
   *   Note that values will be retrieved from the database as a string.
   */
  public $request_data;

  /**
   * @var string
   *   (SQL type: text)
   *   Note that values will be retrieved from the database as a string.
   */
  public $cost_items;

  /**
   * @var string
   *   (SQL type: text)
   *   Note that values will be retrieved from the database as a string.
   */
  public $resources_items;

  /**
   * @var string
   *   (SQL type: decimal(10,2))
   *   Note that values will be retrieved from the database as a string.
   */
  public $amount_requested;

  /**
   * @var bool|string
   *   (SQL type: tinyint)
   *   Note that values will be retrieved from the database as a string.
   */
  public $is_review_content;

  /**
   * @var bool|string
   *   (SQL type: tinyint)
   *   Note that values will be retrieved from the database as a string.
   */
  public $is_review_calculative;

  /**
   * @var bool|string
   *   (SQL type: tinyint)
   *   Note that values will be retrieved from the database as a string.
   */
  public $is_eligible;

  /**
   * @var bool|string
   *   (SQL type: tinyint)
   *   Note that values will be retrieved from the database as a string.
   */
  public $is_in_work;

  /**
   * @var bool|string
   *   (SQL type: tinyint)
   *   Note that values will be retrieved from the database as a string.
   */
  public $is_rejected;

  /**
   * @var bool|string
   *   (SQL type: tinyint)
   *   Note that values will be retrieved from the database as a string.
   */
  public $is_withdrawn;

  /**
   * @var string
   *   (SQL type: mediumtext)
   *   Note that values will be retrieved from the database as a string.
   */
  public $custom_fields;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'civicrm_funding_application_snapshot';
    parent::__construct();
  }

  /**
   * Returns localized title of this entity.
   *
   * @param bool $plural
   *   Whether to return the plural version of the title.
   */
  public static function getEntityTitle($plural = FALSE) {
    return $plural ? E::ts('Application Snapshots') : E::ts('Application Snapshot');
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
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'application_process_id', 'civicrm_funding_application_process', 'id');
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
          'title' => E::ts('ID'),
          'description' => E::ts('Unique FundingApplicationSnapshot ID'),
          'required' => TRUE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_funding_application_snapshot.id',
          'table_name' => 'civicrm_funding_application_snapshot',
          'entity' => 'ApplicationSnapshot',
          'bao' => 'CRM_Funding_DAO_ApplicationSnapshot',
          'localizable' => 0,
          'html' => [
            'type' => 'Number',
          ],
          'readonly' => TRUE,
          'add' => NULL,
        ],
        'application_process_id' => [
          'name' => 'application_process_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Application Process ID'),
          'description' => E::ts('FK to FundingApplicationProcess'),
          'required' => TRUE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_funding_application_snapshot.application_process_id',
          'table_name' => 'civicrm_funding_application_snapshot',
          'entity' => 'ApplicationSnapshot',
          'bao' => 'CRM_Funding_DAO_ApplicationSnapshot',
          'localizable' => 0,
          'FKClassName' => 'CRM_Funding_DAO_ApplicationProcess',
          'html' => [
            'type' => 'EntityRef',
          ],
          'pseudoconstant' => [
            'table' => 'civicrm_funding_application_process',
            'keyColumn' => 'id',
            'labelColumn' => 'title',
            'prefetch' => 'false',
          ],
          'add' => NULL,
        ],
        'status' => [
          'name' => 'status',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Status'),
          'required' => TRUE,
          'maxlength' => 64,
          'size' => CRM_Utils_Type::BIG,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_funding_application_snapshot.status',
          'table_name' => 'civicrm_funding_application_snapshot',
          'entity' => 'ApplicationSnapshot',
          'bao' => 'CRM_Funding_DAO_ApplicationSnapshot',
          'localizable' => 0,
          'html' => [
            'type' => 'Select',
          ],
          'pseudoconstant' => [
            'callback' => 'Civi\Funding\FundingPseudoConstants::getApplicationProcessStatus',
          ],
          'add' => NULL,
        ],
        'creation_date' => [
          'name' => 'creation_date',
          'type' => CRM_Utils_Type::T_TIMESTAMP,
          'title' => E::ts('Creation Date'),
          'required' => TRUE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_funding_application_snapshot.creation_date',
          'table_name' => 'civicrm_funding_application_snapshot',
          'entity' => 'ApplicationSnapshot',
          'bao' => 'CRM_Funding_DAO_ApplicationSnapshot',
          'localizable' => 0,
          'html' => [
            'type' => 'Select Date',
            'formatType' => 'activityDateTime',
          ],
          'add' => NULL,
        ],
        'title' => [
          'name' => 'title',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Title'),
          'required' => TRUE,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_funding_application_snapshot.title',
          'table_name' => 'civicrm_funding_application_snapshot',
          'entity' => 'ApplicationSnapshot',
          'bao' => 'CRM_Funding_DAO_ApplicationSnapshot',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
          'add' => NULL,
        ],
        'short_description' => [
          'name' => 'short_description',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Short Description'),
          'required' => TRUE,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_funding_application_snapshot.short_description',
          'table_name' => 'civicrm_funding_application_snapshot',
          'entity' => 'ApplicationSnapshot',
          'bao' => 'CRM_Funding_DAO_ApplicationSnapshot',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
          'add' => NULL,
        ],
        'start_date' => [
          'name' => 'start_date',
          'type' => CRM_Utils_Type::T_TIMESTAMP,
          'title' => E::ts('Start Date'),
          'required' => FALSE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_funding_application_snapshot.start_date',
          'table_name' => 'civicrm_funding_application_snapshot',
          'entity' => 'ApplicationSnapshot',
          'bao' => 'CRM_Funding_DAO_ApplicationSnapshot',
          'localizable' => 0,
          'html' => [
            'type' => 'Select Date',
            'formatType' => 'activityDateTime',
          ],
          'add' => NULL,
        ],
        'end_date' => [
          'name' => 'end_date',
          'type' => CRM_Utils_Type::T_TIMESTAMP,
          'title' => E::ts('End Date'),
          'required' => FALSE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_funding_application_snapshot.end_date',
          'table_name' => 'civicrm_funding_application_snapshot',
          'entity' => 'ApplicationSnapshot',
          'bao' => 'CRM_Funding_DAO_ApplicationSnapshot',
          'localizable' => 0,
          'html' => [
            'type' => 'Select Date',
            'formatType' => 'activityDateTime',
          ],
          'add' => NULL,
        ],
        'request_data' => [
          'name' => 'request_data',
          'type' => CRM_Utils_Type::T_TEXT,
          'title' => E::ts('Request Data'),
          'required' => TRUE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_funding_application_snapshot.request_data',
          'table_name' => 'civicrm_funding_application_snapshot',
          'entity' => 'ApplicationSnapshot',
          'bao' => 'CRM_Funding_DAO_ApplicationSnapshot',
          'localizable' => 0,
          'serialize' => self::SERIALIZE_JSON,
          'add' => NULL,
        ],
        'cost_items' => [
          'name' => 'cost_items',
          'type' => CRM_Utils_Type::T_TEXT,
          'title' => E::ts('Cost Items'),
          'required' => TRUE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_funding_application_snapshot.cost_items',
          'table_name' => 'civicrm_funding_application_snapshot',
          'entity' => 'ApplicationSnapshot',
          'bao' => 'CRM_Funding_DAO_ApplicationSnapshot',
          'localizable' => 0,
          'serialize' => self::SERIALIZE_JSON,
          'add' => NULL,
        ],
        'resources_items' => [
          'name' => 'resources_items',
          'type' => CRM_Utils_Type::T_TEXT,
          'title' => E::ts('Resources Items'),
          'required' => TRUE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_funding_application_snapshot.resources_items',
          'table_name' => 'civicrm_funding_application_snapshot',
          'entity' => 'ApplicationSnapshot',
          'bao' => 'CRM_Funding_DAO_ApplicationSnapshot',
          'localizable' => 0,
          'serialize' => self::SERIALIZE_JSON,
          'add' => NULL,
        ],
        'amount_requested' => [
          'name' => 'amount_requested',
          'type' => CRM_Utils_Type::T_MONEY,
          'title' => E::ts('Amount Requested'),
          'required' => TRUE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_funding_application_snapshot.amount_requested',
          'dataPattern' => '/^\d{1,10}(\.\d{2})?$/',
          'table_name' => 'civicrm_funding_application_snapshot',
          'entity' => 'ApplicationSnapshot',
          'bao' => 'CRM_Funding_DAO_ApplicationSnapshot',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
          'add' => NULL,
        ],
        'is_review_content' => [
          'name' => 'is_review_content',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => E::ts('Is Review Content'),
          'required' => FALSE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_funding_application_snapshot.is_review_content',
          'table_name' => 'civicrm_funding_application_snapshot',
          'entity' => 'ApplicationSnapshot',
          'bao' => 'CRM_Funding_DAO_ApplicationSnapshot',
          'localizable' => 0,
          'html' => [
            'type' => 'CheckBox',
          ],
          'add' => NULL,
        ],
        'is_review_calculative' => [
          'name' => 'is_review_calculative',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => E::ts('Is Review Calculative'),
          'required' => FALSE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_funding_application_snapshot.is_review_calculative',
          'table_name' => 'civicrm_funding_application_snapshot',
          'entity' => 'ApplicationSnapshot',
          'bao' => 'CRM_Funding_DAO_ApplicationSnapshot',
          'localizable' => 0,
          'html' => [
            'type' => 'CheckBox',
          ],
          'add' => NULL,
        ],
        'is_eligible' => [
          'name' => 'is_eligible',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => E::ts('Is Eligible'),
          'required' => FALSE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_funding_application_snapshot.is_eligible',
          'table_name' => 'civicrm_funding_application_snapshot',
          'entity' => 'ApplicationSnapshot',
          'bao' => 'CRM_Funding_DAO_ApplicationSnapshot',
          'localizable' => 0,
          'html' => [
            'type' => 'CheckBox',
          ],
          'add' => NULL,
        ],
        'is_in_work' => [
          'name' => 'is_in_work',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => E::ts('Is In Work'),
          'required' => TRUE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_funding_application_snapshot.is_in_work',
          'table_name' => 'civicrm_funding_application_snapshot',
          'entity' => 'ApplicationSnapshot',
          'bao' => 'CRM_Funding_DAO_ApplicationSnapshot',
          'localizable' => 0,
          'html' => [
            'type' => 'CheckBox',
          ],
          'add' => NULL,
        ],
        'is_rejected' => [
          'name' => 'is_rejected',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => E::ts('Is Rejected'),
          'required' => TRUE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_funding_application_snapshot.is_rejected',
          'table_name' => 'civicrm_funding_application_snapshot',
          'entity' => 'ApplicationSnapshot',
          'bao' => 'CRM_Funding_DAO_ApplicationSnapshot',
          'localizable' => 0,
          'html' => [
            'type' => 'CheckBox',
          ],
          'add' => NULL,
        ],
        'is_withdrawn' => [
          'name' => 'is_withdrawn',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => E::ts('Is Withdrawn'),
          'required' => TRUE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_funding_application_snapshot.is_withdrawn',
          'table_name' => 'civicrm_funding_application_snapshot',
          'entity' => 'ApplicationSnapshot',
          'bao' => 'CRM_Funding_DAO_ApplicationSnapshot',
          'localizable' => 0,
          'html' => [
            'type' => 'CheckBox',
          ],
          'add' => NULL,
        ],
        'custom_fields' => [
          'name' => 'custom_fields',
          'type' => CRM_Utils_Type::T_TEXT,
          'title' => E::ts('Custom Fields'),
          'required' => TRUE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_funding_application_snapshot.custom_fields',
          'table_name' => 'civicrm_funding_application_snapshot',
          'entity' => 'ApplicationSnapshot',
          'bao' => 'CRM_Funding_DAO_ApplicationSnapshot',
          'localizable' => 0,
          'serialize' => self::SERIALIZE_JSON,
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
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'funding_application_snapshot', $prefix, []);
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
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'funding_application_snapshot', $prefix, []);
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
