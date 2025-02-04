<?php
use CRM_Funding_ExtensionUtil as E;

return [
  [
    'name' => 'SavedSearch_FundingDrawdownsAll',
    'entity' => 'SavedSearch',
    'cleanup' => 'always',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'FundingDrawdownsAll',
        'label' => E::ts('Drawdowns'),
        'api_entity' => 'FundingDrawdown',
        'api_params' => [
          'version' => 4,
          'select' => [
            'id',
            'amount',
            'status:label',
            'acception_date',
            'FundingDrawdown_FundingPayoutProcess_payout_process_id_01_FundingPayoutProcess_FundingCase_funding_case_id_01.identifier',
            'FundingDrawdown_FundingPayoutProcess_payout_process_id_01_FundingPayoutProcess_FundingCase_funding_case_id_01.recipient_contact_id.display_name',
            'CAN_review',
          ],
          'orderBy' => [],
          'where' => [],
          'groupBy' => [],
          'join' => [
            [
              'FundingPayoutProcess AS FundingDrawdown_FundingPayoutProcess_payout_process_id_01',
              'INNER',
              [
                'payout_process_id',
                '=',
                'FundingDrawdown_FundingPayoutProcess_payout_process_id_01.id',
              ],
            ],
            [
              'FundingCase AS FundingDrawdown_FundingPayoutProcess_payout_process_id_01_FundingPayoutProcess_FundingCase_funding_case_id_01',
              'INNER',
              [
                'FundingDrawdown_FundingPayoutProcess_payout_process_id_01.funding_case_id',
                '=',
                'FundingDrawdown_FundingPayoutProcess_payout_process_id_01_FundingPayoutProcess_FundingCase_funding_case_id_01.id',
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
    'name' => 'SavedSearch_FundingDrawdownsAll_SearchDisplay_Table',
    'entity' => 'SearchDisplay',
    'cleanup' => 'always',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'Table',
        'label' => E::ts('Table'),
        'saved_search_id.name' => 'FundingDrawdownsAll',
        'type' => 'table',
        'settings' => [
          'description' => NULL,
          'sort' => [],
          'limit' => 10,
          'pager' => [],
          'placeholder' => 5,
          'columns' => [
            [
              'type' => 'field',
              'key' => 'FundingDrawdown_FundingPayoutProcess_payout_process_id_01_FundingPayoutProcess_FundingCase_funding_case_id_01.identifier',
              'dataType' => 'String',
              'label' => E::ts('Identifier'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'FundingDrawdown_FundingPayoutProcess_payout_process_id_01_FundingPayoutProcess_FundingCase_funding_case_id_01.recipient_contact_id.display_name',
              'dataType' => 'String',
              'label' => E::ts('Recipient'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'amount',
              'dataType' => 'Money',
              'label' => E::ts('Amount'),
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
              'key' => 'acception_date',
              'dataType' => 'Timestamp',
              'label' => E::ts('Acception Date'),
              'sortable' => TRUE,
            ],
            [
              'size' => 'btn-xs',
              'links' => [
                [
                  'path' => 'civicrm/funding/drawdown/accept?drawdownId=[id]',
                  'icon' => 'fa-thumbs-up',
                  'text' => E::ts('Accept'),
                  'style' => 'success',
                  'condition' => [
                    'CAN_review',
                    '=',
                    TRUE,
                  ],
                  'task' => '',
                  'entity' => '',
                  'action' => '',
                  'join' => '',
                  'target' => '',
                ],
                [
                  'path' => 'civicrm/funding/drawdown/reject?drawdownId=[id]civicrm/',
                  'icon' => 'fa-thumbs-down',
                  'text' => E::ts('Reject'),
                  'style' => 'danger',
                  'condition' => [
                    'CAN_review',
                    '=',
                    TRUE,
                  ],
                  'task' => '',
                  'entity' => '',
                  'action' => '',
                  'join' => '',
                  'target' => '',
                ],
                [
                  'path' => 'civicrm/funding/drawdown-document/download?drawdownId=[id]civicrm/',
                  'icon' => 'fa-external-link',
                  'text' => E::ts('Download Document'),
                  'style' => 'default',
                  'condition' => [
                    'acception_date',
                    'IS NOT EMPTY',
                  ],
                  'task' => '',
                  'entity' => '',
                  'action' => '',
                  'join' => '',
                  'target' => '_blank',
                ],
              ],
              'type' => 'buttons',
              'alignment' => 'text-right',
              'label' => E::ts('Actions'),
            ],
          ],
          'actions' => [
            'download',
          ],
          'classes' => [
            'table',
            'table-striped',
          ],
          'actions_display_mode' => 'menu',
        ],
      ],
      'match' => [
        'saved_search_id',
        'name',
      ],
    ],
  ],
];
