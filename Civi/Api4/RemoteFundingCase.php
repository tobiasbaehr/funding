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

use Civi\Funding\Api4\AbstractRemoteFundingEntity;
use Civi\Funding\Api4\Action\Remote\DAOGetAction;
use Civi\Funding\Api4\Action\Remote\FundingCase\GetNewApplicationFormAction;
use Civi\Funding\Api4\Action\Remote\FundingCase\SubmitNewApplicationFormAction;
use Civi\Funding\Api4\Action\Remote\FundingCase\ValidateNewApplicationFormAction;

final class RemoteFundingCase extends AbstractRemoteFundingEntity {

  public static function get(): DAOGetAction {
    return new DAOGetAction(static::getEntityName());
  }

  public static function getNewApplicationForm(): GetNewApplicationFormAction {
    return \Civi::service(GetNewApplicationFormAction::class);
  }

  public static function submitNewApplicationForm(): SubmitNewApplicationFormAction {
    return \Civi::service(SubmitNewApplicationFormAction::class);
  }

  public static function validateNewApplicationForm(): ValidateNewApplicationFormAction {
    return \Civi::service(ValidateNewApplicationFormAction::class);
  }

}
