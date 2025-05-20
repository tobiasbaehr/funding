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

namespace Civi\RemoteTools\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class FilterPossiblePermissionsEvent extends Event {

  private string $entityName;

  /**
   * @phpstan-var array<string, string>
   */
  private array $permissions;

  public static function getName(string $entityName): string {
    return static::class . '@' . $entityName;
  }

  /**
   * @phpstan-param array<string, string> $permissions
   *   Permissions mapped to labels.
   */
  public function __construct(string $entityName, array $permissions) {
    $this->entityName = $entityName;
    $this->permissions = $permissions;
  }

  public function getEntityName(): string {
    return $this->entityName;
  }

  /**
   * @phpstan-return array<string, string> $permissions
   *   Permissions mapped to labels.
   */
  public function getPermissions(): array {
    return $this->permissions;
  }

  /**
   * @phpstan-param array<string> $permissions
   */
  public function keepPermissions(array $permissions): self {
    $this->permissions = array_filter(
      $this->permissions,
      fn(string $permission) => in_array($permission, $permissions, TRUE),
      ARRAY_FILTER_USE_KEY
    );

    return $this;
  }

  public function keepPermissionsByPrefix(string $prefix): self {
    $this->permissions = array_filter(
      $this->permissions,
      fn(string $permission) => str_starts_with($permission, $prefix),
      ARRAY_FILTER_USE_KEY
    );

    return $this;
  }

  /**
   * @phpstan-param list<string> $prefixes
   */
  public function keepPermissionsByPrefixes(array $prefixes): self {
    $this->permissions = array_filter(
      $this->permissions,
      function (string $permission) use ($prefixes): bool {
        foreach ($prefixes as $prefix) {
          if (str_starts_with($permission, $prefix)) {
            return TRUE;
          }
        }

        return FALSE;
      },
      ARRAY_FILTER_USE_KEY
    );

    return $this;
  }

  public function removePermission(string $permission): self {
    $this->removePermissions([$permission]);

    return $this;
  }

  /**
   * @phpstan-param array<string> $permissions
   */
  public function removePermissions(array $permissions): self {
    $this->permissions = array_filter(
      $this->permissions,
      fn(string $permission) => !in_array($permission, $permissions, TRUE),
      ARRAY_FILTER_USE_KEY
    );

    return $this;
  }

  public function removePermissionsByPrefix(string $prefix): self {
    $this->permissions = array_filter(
      $this->permissions, fn(string $permission) => !str_starts_with($permission, $prefix),
      ARRAY_FILTER_USE_KEY
    );

    return $this;
  }

}
