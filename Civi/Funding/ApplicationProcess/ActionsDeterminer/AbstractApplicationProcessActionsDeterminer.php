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

namespace Civi\Funding\ApplicationProcess\ActionsDeterminer;

use Civi\Funding\Entity\ApplicationProcessEntityBundle;

/**
 * @phpstan-type statusPermissionsActionMapT array<string|null, array<string, list<string>>>
 */
abstract class AbstractApplicationProcessActionsDeterminer implements ApplicationProcessActionsDeterminerInterface {

  /**
   * @phpstan-var statusPermissionsActionMapT
   */
  private array $statusPermissionActionsMap;

  /**
   * @phpstan-param statusPermissionsActionMapT $statusPermissionActionsMap
   */
  public function __construct(array $statusPermissionActionsMap) {
    $this->statusPermissionActionsMap = $statusPermissionActionsMap;
  }

  public function getActions(ApplicationProcessEntityBundle $applicationProcessBundle, array $statusList): array {
    return $this->doGetActions(
      $applicationProcessBundle->getApplicationProcess()->getStatus(),
      $applicationProcessBundle->getFundingCase()->getPermissions()
    );
  }

  public function getInitialActions(array $permissions): array {
    return $this->doGetActions(NULL, $permissions);
  }

  public function isActionAllowed(
    string $action,
    ApplicationProcessEntityBundle $applicationProcessBundle,
    array $statusList
  ): bool {
    return $this->isAnyActionAllowed([$action], $applicationProcessBundle, $statusList);
  }

  public function isAnyActionAllowed(
    array $actions,
    ApplicationProcessEntityBundle $applicationProcessBundle,
    array $statusList
  ): bool {
    return [] !== array_intersect($this->getActions($applicationProcessBundle, $statusList), $actions);
  }

  public function isEditAllowed(
    ApplicationProcessEntityBundle $applicationProcessBundle,
    array $statusList
  ): bool {
    return $this->isAnyActionAllowed(['save', 'apply', 'update'], $applicationProcessBundle, $statusList);
  }

  /**
   * @phpstan-param array<string> $permissions
   *
   * @phpstan-return list<string>
   */
  private function doGetActions(?string $status, array $permissions): array {
    $actions = [];
    foreach ($permissions as $permission) {
      $actions = \array_merge($actions, $this->statusPermissionActionsMap[$status][$permission] ?? []);
    }

    return \array_values(\array_unique($actions));
  }

}
