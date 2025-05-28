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

namespace Civi\Funding\Fixtures;

use Civi\Api4\FundingClearingProcess;
use Civi\Funding\Entity\ClearingProcessEntity;

final class ClearingProcessFixture {

  /**
   * @phpstan-param array<string, mixed> $values
   *
   * @throws \CRM_Core_Exception
   */
  public static function addFixture(int $applicationProcessId, array $values = []): ClearingProcessEntity {
    $result = FundingClearingProcess::create(FALSE)
      ->setValues($values + [
        'application_process_id' => $applicationProcessId,
        'status' => 'draft',
        'creation_date' => date('Y-m-d H:i:s'),
        'modification_date' => date('Y-m-d H:i:s'),
        'start_date' => NULL,
        'end_date' => NULL,
        'report_data' => [],
        'is_review_content' => NULL,
        'reviewer_cont_contact_id' => NULL,
        'is_review_calculative' => NULL,
        'reviewer_calc_contact_id' => NULL,
      ])->execute();

    return ClearingProcessEntity::singleFromApiResult($result)->reformatDates();
  }

}
