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

namespace Civi\Funding\Api4\Traits;

use Civi\Funding\Api4\Permissions;

/**
 * Permissions for funding entities related to administration. Those entities
 * can be read, updated, and deleted by funding admins.
 *
 * @see \Civi\Funding\Api4\Traits\AccessROAdministerRWPermissionsTrait
 */
trait AdministerPermissionsTrait {

  /**
   * @return array<string, array<string|string[]>>
   */
  public static function permissions(): array {
    return [
      'meta' => [
        Permissions::ACCESS_CIVICRM,
        [
          Permissions::ACCESS_FUNDING,
          Permissions::ADMINISTER_FUNDING,
        ],
      ],
      'default' => [Permissions::ACCESS_CIVICRM, Permissions::ADMINISTER_FUNDING],
    ];
  }

}
