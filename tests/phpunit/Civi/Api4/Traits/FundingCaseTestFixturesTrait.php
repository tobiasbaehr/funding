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

namespace Civi\Api4\Traits;

use Civi\Api4\Contact;
use Civi\Api4\FundingCase;
use Civi\Api4\FundingCaseContactRelation;
use Civi\Api4\Relationship;
use Civi\Api4\RelationshipType;
use Civi\Funding\Fixtures\FundingCaseContactRelationFixture;
use Civi\Funding\Fixtures\FundingCaseTypeFixture;
use Civi\Funding\Fixtures\FundingProgramFixture;

trait FundingCaseTestFixturesTrait {

  protected int $associatedContactId = -1;

  protected int $associatedContactIdApplicationAndReview = -1;

  protected int $associatedContactIdNoPermissions = -1;

  protected int $relatedABContactId = -1;

  protected int $relatedBAContactId = -1;

  protected int $permittedFundingCaseId = -1;

  protected int $notPermittedContactId = -1;

  /**
   * @throws \API_Exception
   */
  protected function addRemoteFixtures(): void {
    $this->addFixtures(
      ['application_foo', 'application_bar', 'review_baz'],
      ['application_c', 'application_d', 'review_e']
    );
  }

  /**
   * @throws \API_Exception
   */
  protected function addInternalFixtures(): void {
    $this->addFixtures(['review_baz'], ['review_e']);

    $this->associatedContactIdApplicationAndReview = Contact::create()
      ->setValues([
        'contact_type' => 'Individual',
        'first_name' => 'Associated',
        'last_name' => 'User',
      ])->execute()->first()['id'];

    FundingCaseContactRelationFixture::addContact(
      $this->associatedContactIdApplicationAndReview,
      $this->permittedFundingCaseId,
      ['application_foo', 'review_bar']
    );
  }

  /**
   * @phpstan-param array<string> $associatedContactPermissions
   * @phpstan-param array<string> $permittedRelationshipTypePermissions
   *
   * @throws \API_Exception
   */
  private function addFixtures(array $associatedContactPermissions, array $permittedRelationshipTypePermissions): void {
    $fundingProgramId = FundingProgramFixture::addFixture(['title' => 'Foo'])->getId();

    $fundingCaseTypeId = FundingCaseTypeFixture::addFixture()->getId();

    $recipientContactId = Contact::create()
      ->setValues([
        'contact_type' => 'Organization',
        'legal_name' => 'Recipient Organization',
      ])->execute()->first()['id'];

    $this->permittedFundingCaseId = FundingCase::create()
      ->setValues([
        'funding_program_id' => $fundingProgramId,
        'funding_case_type_id' => $fundingCaseTypeId,
        'status' => 'open',
        'creation_date' => '2022-06-23 10:00:00',
        'modification_date' => '2022-06-24 10:00:00',
        'recipient_contact_id' => $recipientContactId,
      ])->execute()->first()['id'];

    FundingCase::create()
      ->setValues([
        'funding_program_id' => $fundingProgramId,
        'funding_case_type_id' => $fundingCaseTypeId,
        'status' => 'open',
        'creation_date' => '2022-06-23 10:00:00',
        'modification_date' => '2022-06-24 10:00:00',
        'recipient_contact_id' => $recipientContactId,
      ])->execute();

    $permittedRelationshipTypeId = RelationshipType::create()
      ->setValues([
        'name_a_b' => 'permitted',
        'name_b_a' => 'permitted',
        'contact_type_a' => 'Individual',
        'contact_type_b' => 'Individual',
      ])->execute()->first()['id'];

    $notPermittedRelationshipTypeId = RelationshipType::create()
      ->setValues([
        'name_b_a' => 'foo',
        'name_a_b' => 'bar',
        'contact_type_a' => 'Individual',
        'contact_type_b' => 'Individual',
      ])->execute()->first()['id'];

    $this->associatedContactIdNoPermissions = Contact::create()
      ->setValues([
        'contact_type' => 'Individual',
        'first_name' => 'Associated No Permissions',
        'last_name' => 'User',
      ])->execute()->first()['id'];

    $contactRelationId = FundingCaseContactRelationFixture::addContact(
      $this->associatedContactIdNoPermissions,
      $this->permittedFundingCaseId,
      NULL)['id'];

    $this->associatedContactId = Contact::create()
      ->setValues([
        'contact_type' => 'Individual',
        'first_name' => 'Associated',
        'last_name' => 'User',
      ])->execute()->first()['id'];

    FundingCaseContactRelationFixture::addContact(
      $this->associatedContactId,
      $this->permittedFundingCaseId,
      $associatedContactPermissions);

    FundingCaseContactRelation::create()
      ->setValues([
        'funding_case_id' => $this->permittedFundingCaseId,
        'entity_table' => 'civicrm_relationship_type',
        'entity_id' => $permittedRelationshipTypeId,
        'parent_id' => $contactRelationId,
        'permissions' => $permittedRelationshipTypePermissions,
      ])->execute();

    $this->relatedABContactId = Contact::create()
      ->setValues([
        'contact_type' => 'Individual',
        'first_name' => 'RelatedAB',
        'last_name' => 'User',
      ])
      ->execute()->first()['id'];

    Relationship::create()
      ->setValues([
        'contact_id_a' => $this->associatedContactIdNoPermissions,
        'contact_id_b' => $this->relatedABContactId,
        'relationship_type_id' => $permittedRelationshipTypeId,
      ])->execute();

    $this->relatedBAContactId = Contact::create()
      ->setValues([
        'contact_type' => 'Individual',
        'first_name' => 'RelatedBA',
        'last_name' => 'User',
      ])
      ->execute()->first()['id'];

    Relationship::create()
      ->setValues([
        'contact_id_a' => $this->relatedBAContactId,
        'contact_id_b' => $this->associatedContactIdNoPermissions,
        'relationship_type_id' => $permittedRelationshipTypeId,
      ])->execute();

    $this->notPermittedContactId = Contact::create()
      ->setValues([
        'contact_type' => 'Individual',
        'first_name' => 'NotPermitted',
        'last_name' => 'User',
      ])
      ->execute()->first()['id'];

    Relationship::create()
      ->setValues([
        'contact_id_a' => $this->notPermittedContactId,
        'contact_id_b' => $this->associatedContactIdNoPermissions,
        'relationship_type_id' => $notPermittedRelationshipTypeId,
      ])->execute();
  }

}
