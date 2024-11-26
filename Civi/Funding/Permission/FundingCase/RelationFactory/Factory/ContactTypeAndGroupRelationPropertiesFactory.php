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

namespace Civi\Funding\Permission\FundingCase\RelationFactory\Factory;

use Civi\Funding\Entity\FundingCaseEntity;
use Civi\Funding\Permission\FundingCase\RelationFactory\RelationPropertiesFactoryInterface;
use Civi\Funding\Permission\FundingCase\RelationFactory\Types\ContactTypeAndGroup;

/**
 * @codeCoverageIgnore
 */
final class ContactTypeAndGroupRelationPropertiesFactory implements RelationPropertiesFactoryInterface {

  public static function getSupportedFactoryType(): string {
    return ContactTypeAndGroup::NAME;
  }

  public function createRelationProperties(
    array $properties,
    FundingCaseEntity $fundingCase
  ): array {
    /** @phpstan-param array{contactTypeIds?: list<int>, groupIds?: list<int>} $properties */
    return $properties;
  }

  public function getRelationType(): string {
    return \Civi\Funding\Permission\ContactRelation\Types\ContactTypeAndGroup::NAME;
  }

}
