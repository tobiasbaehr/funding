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

namespace Civi\Funding\SammelantragKurs\FundingCase\Actions;

use Civi\Funding\FundingCase\Actions\FundingCaseActionsDeterminerDecorator;
use Civi\Funding\FundingCase\Actions\DefaultFundingCaseActionsDeterminer;
use Civi\Funding\SammelantragKurs\Application\Actions\KursApplicationActionsDeterminer;
use Civi\Funding\SammelantragKurs\Application\Actions\KursApplicationActionStatusInfo;
use Civi\Funding\SammelantragKurs\Traits\KursSupportedFundingCaseTypesTrait;

final class KursCaseActionsDeterminer extends FundingCaseActionsDeterminerDecorator {

  use KursSupportedFundingCaseTypesTrait;

  private KursApplicationActionsDeterminer $applicationActionsDeterminer;

  public function __construct(
    KursApplicationActionsDeterminer $applicationActionsDeterminer,
    KursApplicationActionStatusInfo $statusInfo
  ) {
    parent::__construct(new DefaultFundingCaseActionsDeterminer($statusInfo));
    $this->applicationActionsDeterminer = $applicationActionsDeterminer;
  }

  /**
   * @inheritDoc
   */
  public function getActions(
    string $status,
    array $applicationProcessStatusList,
    array $permissions
  ): array {
    $actions = [];
    foreach ($applicationProcessStatusList as $applicationProcessStatus) {
      if ($this->applicationActionsDeterminer->isActionAllowed(
        'apply',
        $applicationProcessStatus,
        $applicationProcessStatusList,
        $permissions
      )) {
        $actions[] = 'apply';
        break;
      }
      elseif ($this->applicationActionsDeterminer->isActionAllowed(
        'review',
        $applicationProcessStatus,
        $applicationProcessStatusList,
        $permissions
      )) {
        $actions[] = 'review';
        break;
      }
    }

    return array_unique(array_merge(
      $actions,
      parent::getActions($status, $applicationProcessStatusList, $permissions)
    ));
  }

  /**
   * @inheritDoc
   */
  public function getInitialActions(array $permissions): array {
    if (in_array('application_create', $permissions, TRUE)) {
      return ['save'];
    }

    return [];
  }

}
