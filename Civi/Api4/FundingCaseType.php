<?php
/*
 * Copyright (C) 2022 SYSTOPIA GmbH
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

namespace Civi\Api4;

use Civi\Api4\Generic\Traits\ManagedEntity;
use Civi\Funding\Api4\Action\FundingCaseType\GetAction;
use Civi\Funding\Api4\Action\FundingCaseType\GetByFundingProgramIdAction;
use Civi\Funding\Api4\Action\FundingCaseType\GetFieldsAction;
use Civi\Funding\Api4\Action\FundingCaseType\SaveAction;
use Civi\Funding\Api4\Action\FundingCaseType\UpdateAction;
use Civi\Funding\Api4\Traits\AccessPermissionsTrait;

/**
 * FundingCaseType entity.
 *
 * Provided by the Funding Program Manager extension.
 *
 * @package Civi\Api4
 */
class FundingCaseType extends Generic\DAOEntity {

  use AccessPermissionsTrait;

  use ManagedEntity;

  public static function get($checkPermissions = TRUE) {
    return \Civi::service(GetAction::class)->setCheckPermissions($checkPermissions);
  }

  public static function getByFundingProgramId(bool $checkPermissions = TRUE): GetByFundingProgramIdAction {
    return \Civi::service(GetByFundingProgramIdAction::class)->setCheckPermissions($checkPermissions);
  }

  public static function getFields($checkPermissions = TRUE) {
    return (new GetFieldsAction())->setCheckPermissions($checkPermissions);
  }

  public static function save($checkPermissions = TRUE) {
    return (new SaveAction())->setCheckPermissions($checkPermissions);
  }

  public static function update($checkPermissions = TRUE) {
    return \Civi::service(UpdateAction::class)->setCheckPermissions($checkPermissions);
  }

}
