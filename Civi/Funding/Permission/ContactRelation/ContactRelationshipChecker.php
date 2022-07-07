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

namespace Civi\Funding\Permission\ContactRelation;

use Civi\Api4\Relationship;
use Civi\Funding\Permission\ContactRelationCheckerInterface;
use Civi\RemoteTools\Api4\Api4Interface;
use Civi\RemoteTools\Api4\Query\Comparison;
use Civi\RemoteTools\Api4\Query\CompositeCondition;
use Webmozart\Assert\Assert;

final class ContactRelationshipChecker implements ContactRelationCheckerInterface {

  private Api4Interface $api4;

  public function __construct(Api4Interface $api4) {
    $this->api4 = $api4;
  }

  /**
   * @inheritDoc
   *
   * @throws \API_Exception
   */
  public function hasRelation(int $contactId, array $contactRelation, ?array $parentContactRelation): bool {
    Assert::notNull($parentContactRelation);
    $relationshipTypeId = $contactRelation['entity_id'];
    $relatedContactId = $parentContactRelation['entity_id'];

    $action = Relationship::get()
      ->addSelect('id')
      ->addWhere('relationship_type_id', '=', $relationshipTypeId)
      ->addClause('OR',
        CompositeCondition::new('AND',
          Comparison::new('contact_id_a', '=', $contactId),
          Comparison::new('contact_id_b', '=', $relatedContactId),
        )->toArray(),
        CompositeCondition::new('AND',
          Comparison::new('contact_id_a', '=', $relatedContactId),
          Comparison::new('contact_id_b', '=', $contactId),
        )->toArray(),
      );

    return $this->api4->executeAction($action)->rowCount >= 1;
  }

  /**
   * @inheritDoc
   */
  public function supportsRelation(array $contactRelation, ?array $parentContactRelation): bool {
    if ('civicrm_relationship_type' === $contactRelation['entity_table'] && NULL !== $contactRelation['parent_id']) {
      Assert::notNull($parentContactRelation);
      return 'civicrm_contact' === $parentContactRelation['entity_table'];
    }

    return FALSE;
  }

}
