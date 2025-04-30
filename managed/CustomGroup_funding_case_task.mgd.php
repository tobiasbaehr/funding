<?php

use Civi\Funding\ActivityTypeNames;
use CRM_Funding_ExtensionUtil as E;

return [
  [
    'name' => 'CustomGroup_funding_case_task',
    'entity' => 'CustomGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'funding_case_task',
        'table_name' => 'civicrm_value_funding_case_task',
        'title' => E::ts('Funding Case Task'),
        'extends' => 'Activity',
        'extends_entity_column_value:name' => ActivityTypeNames::getTasks(),
        'style' => 'Inline',
        'collapse_display' => TRUE,
        'help_pre' => '',
        'help_post' => '',
        'collapse_adv_display' => TRUE,
        'icon' => '',
      ],
      'match' => [
        'name',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_funding_case_task_CustomField_type',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'funding_case_task',
        'name' => 'type',
        'label' => E::ts('Type'),
        'html_type' => 'Text',
        'is_required' => TRUE,
        'is_searchable' => TRUE,
        'text_length' => 255,
        'note_columns' => 60,
        'note_rows' => 4,
        'column_name' => 'type',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_funding_case_task_CustomField_affected_identifier',
    'entity' => 'CustomField',
    'cleanup' => 'always',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'funding_case_task',
        'name' => 'affected_identifier',
        'label' => E::ts('Affected Identifier'),
        'html_type' => 'Text',
        'is_required' => TRUE,
        'text_length' => 255,
        'note_columns' => 60,
        'note_rows' => 4,
        'column_name' => 'affected_identifier',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_funding_case_task_CustomField_funding_case_id',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'funding_case_task',
        'name' => 'funding_case_id',
        'label' => E::ts('Funding Case'),
        'data_type' => 'EntityReference',
        'html_type' => 'Autocomplete-Select',
        'is_required' => TRUE,
        'text_length' => 255,
        'note_columns' => 60,
        'note_rows' => 4,
        'column_name' => 'funding_case_id',
        'fk_entity' => 'FundingCase',
        'fk_entity_on_delete' => 'cascade',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_funding_case_task_CustomField_required_permissions',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'funding_case_task',
        'name' => 'required_permissions',
        'label' => E::ts('Required Permissions'),
        'data_type' => 'Memo',
        'html_type' => 'TextArea',
        'attributes' => 'rows=4, cols=60',
        'note_columns' => 60,
        'note_rows' => 4,
        'column_name' => 'required_permissions',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
];
