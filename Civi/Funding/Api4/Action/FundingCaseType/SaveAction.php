<?php
/*
 * Copyright (C) 2024 SYSTOPIA GmbH
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

namespace Civi\Funding\Api4\Action\FundingCaseType;

use Civi\Api4\FundingCaseType;
use Civi\Api4\Generic\DAOSaveAction;
use CRM_Funding_ExtensionUtil as E;

final class SaveAction extends DAOSaveAction {

  public function __construct() {
    parent::__construct(FundingCaseType::getEntityName(), 'save');
  }

  /**
   * @phpstan-param list<array<string, mixed>> $items
   *
   * @phpstan-return list<array<string, mixed>>
   */
  protected function writeObjects($items): array {
    $result = parent::writeObjects($items);

    foreach ($items as $index => $item) {
      if (!isset($item['id'])) {
        continue;
      }

      $updateValues = [];
      if (isset($item['transfer_contract_template_file_id'])) {
        $updateValues['transfer_contract_template_file_id'] = $item['transfer_contract_template_file_id'];
      }
      if (isset($item['payment_instruction_template_file_id'])) {
        $updateValues['payment_instruction_template_file_id'] = $item['payment_instruction_template_file_id'];
      }

      if ([] !== $updateValues) {
        FundingCaseType::update(FALSE)
          ->setValues($updateValues)
          ->addWhere('id', '=', $item['id'])
          ->execute();

        $result[$index] = array_merge($result[$index], $updateValues);
      }
    }

    return $result;
  }

}
