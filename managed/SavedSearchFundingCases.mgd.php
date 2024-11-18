<?php
/*
 * Copyright (C) 2023 SYSTOPIA GmbH
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published by
 *  the Free Software Foundation in version 3.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

declare(strict_types = 1);

use CRM_Funding_ExtensionUtil as E;

return [
  [
    'name' => 'SavedSearch_funding_cases',
    'entity' => 'SavedSearch',
    'cleanup' => 'always',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'funding_cases',
        'label' => E::ts('Funding Cases'),
        'api_entity' => 'FundingCase',
        'api_params' => [
          'version' => 4,
          'select' => [
            'id',
            'identifier',
            'status:label',
            'FundingCase_FundingProgram_funding_program_id_01.title',
            'FundingCase_FundingCaseType_funding_case_type_id_01.title',
            'recipient_contact_id.display_name',
            'amount_approved',
            'amount_paid_out',
            'amount_admitted',
            'amount_cleared',
            'creation_date',
            'modification_date',
            'application_process_progress',
            'debug_num_applications with status',
            'debug_num_applications eligible',
          ],
          'orderBy' => [
            'id' => 'DESC',
          ],
          'where' => [],
          'groupBy' => [],
          'join' => [
            [
              'FundingProgram AS FundingCase_FundingProgram_funding_program_id_01',
              'INNER',
              [
                'funding_program_id',
                '=',
                'FundingCase_FundingProgram_funding_program_id_01.id',
              ],
            ],
            [
              'FundingCaseType AS FundingCase_FundingCaseType_funding_case_type_id_01',
              'INNER',
              [
                'funding_case_type_id',
                '=',
                'FundingCase_FundingCaseType_funding_case_type_id_01.id',
              ],
            ],
          ],
          'having' => [],
        ],
      ],
      'match' => [
        'name',
      ],
    ],
  ],
  [
    'name' => 'SavedSearch_funding_cases_SearchDisplay_table',
    'entity' => 'SearchDisplay',
    'cleanup' => 'always',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'table',
        'label' => E::ts('Table'),
        'saved_search_id.name' => 'funding_cases',
        'type' => 'table',
        'settings' => [
          'description' => NULL,
          'sort' => [],
          'limit' => 60,
          'pager' => [
            'show_count' => FALSE,
            'expose_limit' => TRUE,
          ],
          'placeholder' => 5,
          'columns' => [
            [
              'type' => 'field',
              'key' => 'id',
              'dataType' => 'Integer',
              'label' => E::ts('ID'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'identifier',
              'dataType' => 'String',
              'label' => E::ts('Identifier'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'status:label',
              'dataType' => 'String',
              'label' => E::ts('Status'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'FundingCase_FundingProgram_funding_program_id_01.title',
              'dataType' => 'String',
              'label' => E::ts('Funding Program'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'FundingCase_FundingCaseType_funding_case_type_id_01.title',
              'dataType' => 'String',
              'label' => E::ts('Funding Case Type'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'recipient_contact_id.display_name',
              'dataType' => 'String',
              'label' => E::ts('Recipient'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'application_process_progress',
              'dataType' => 'Integer',
              'label' => E::ts('Review Progress'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'amount_approved',
              'dataType' => 'Money',
              'label' => E::ts('Amount Approved'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'debug_num_applications eligible',
              'dataType' => 'Integer',
              'label' => E::ts('Debug num applications eligible'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'debug_num_applications with status',
              'dataType' => 'Integer',
              'label' => E::ts('Debug num applications status'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'amount_paid_out',
              'dataType' => 'Money',
              'label' => E::ts('Amount Paid Out'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'amount_admitted',
              'dataType' => 'Money',
              'label' => E::ts('Amount Admitted'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'amount_cleared',
              'dataType' => 'Money',
              'label' => E::ts('Amount Cleared'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'creation_date',
              'dataType' => 'Timestamp',
              'label' => E::ts('Creation Date'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'modification_date',
              'dataType' => 'Timestamp',
              'label' => E::ts('Modification Date'),
              'sortable' => TRUE,
            ],
            [
              'text' => E::ts('Actions'),
              'style' => 'default',
              'size' => 'btn-sm',
              'icon' => 'fa-bars',
              'links' => [
                [
                  'path' => 'civicrm/a#funding/case/[id]',
                  'icon' => 'fa-folder-open-o',
                  'text' => E::ts('Open case'),
                  'style' => 'default',
                  'condition' => [],
                  'entity' => '',
                  'action' => '',
                  'join' => '',
                  'target' => '',
                  'task' => '',
                ],
                [
                  'path' => 'civicrm/a/#/funding/case/[id]/permissions',
                  'icon' => 'fa-pencil-square-o',
                  'text' => E::ts('Edit permissions'),
                  'style' => 'default',
                  'condition' => [
                    'check user permission',
                    '=',
                    'administer Funding',
                  ],
                  'entity' => '',
                  'action' => '',
                  'join' => '',
                  'target' => '',
                  'task' => '',
                ],
              ],
              'type' => 'menu',
              'alignment' => 'text-right',
            ],
          ],
          'actions' => FALSE,
          'classes' => [
            'table',
            'table-striped',
          ],
        ],
      ],
      'match' => [
        'saved_search_id',
        'name',
      ],
    ],
  ],
];
