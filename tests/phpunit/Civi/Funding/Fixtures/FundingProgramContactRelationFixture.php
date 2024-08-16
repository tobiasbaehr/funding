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

namespace Civi\Funding\Fixtures;

use Civi\Api4\FundingProgramContactRelation;
use Civi\Funding\FundingProgram\FundingProgramManager;

final class FundingProgramContactRelationFixture {

  /**
   * @phpstan-param list<string> $permissions
   *
   * @phpstan-return array<string, scalar|null>&array{id: int}
   *
   * @throws \CRM_Core_Exception
   */
  public static function addContact(int $contactId, int $fundingProgramId, array $permissions): array {
    // Contact IDs are stored as string (see comment in ContactChecker), so we
    // do the same here.
    return self::addFixture($fundingProgramId, 'Contact', ['contactId' => (string) $contactId], $permissions);
  }

  /**
   * @phpstan-param array<string, mixed> $properties
   * @phpstan-param list<string> $permissions
   *
   * @phpstan-return array<string, scalar|null>&array{id: int}
   *
   * @throws \CRM_Core_Exception
   */
  public static function addFixture(int $fundingProgramId, string $type, array $properties, array $permissions): array {
    // @phpstan-ignore-next-line
    \Civi::service(FundingProgramManager::class)->clearCache();

    return FundingProgramContactRelation::create(FALSE)
      ->setValues([
        'funding_program_id' => $fundingProgramId,
        'type' => $type,
        'properties' => $properties,
        'permissions' => $permissions,
      ])->execute()->first();
  }

}
