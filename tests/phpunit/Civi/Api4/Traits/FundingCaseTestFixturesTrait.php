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

use Civi\Api4\Relationship;
use Civi\Api4\RelationshipType;
use Civi\Funding\Entity\FundingCaseEntity;
use Civi\Funding\Fixtures\ApplicationProcessFixture;
use Civi\Funding\Fixtures\ContactFixture;
use Civi\Funding\Fixtures\FundingCaseContactRelationFixture;
use Civi\Funding\Fixtures\FundingCaseFixture;
use Civi\Funding\Fixtures\FundingCaseTypeFixture;
use Civi\Funding\Fixtures\FundingProgramContactRelationFixture;
use Civi\Funding\Fixtures\FundingProgramFixture;

trait FundingCaseTestFixturesTrait {

  protected int $applicationProcessId = -1;

  protected int $associatedContactId = -1;

  protected int $associatedContactIdApplicationAndReview = -1;

  protected int $associatedContactIdNoPermissions = -1;

  protected int $fundingCaseTypeId = -1;

  protected int $relatedABContactId = -1;

  protected int $relatedBAContactId = -1;

  protected int $notPermittedContactId = -1;

  protected FundingCaseEntity $permittedFundingCase;

  protected int $permittedFundingCaseId = -1;

  /**
   * @throws \CRM_Core_Exception
   */
  protected function addRemoteFixtures(): void {
    $this->addFixtures(
      ['application_foo', 'application_bar', 'review_baz'],
      ['application_c', 'application_d', 'review_e']
    );
  }

  /**
   * @throws \CRM_Core_Exception
   */
  protected function addInternalFixtures(): void {
    $this->addFixtures(['review_baz'], ['review_e']);

    $this->associatedContactIdApplicationAndReview = ContactFixture::addIndividual([
      'first_name' => 'Associated',
      'last_name' => 'User',
    ])['id'];

    FundingCaseContactRelationFixture::addContact(
      $this->associatedContactIdApplicationAndReview,
      $this->permittedFundingCaseId,
      ['application_foo', 'review_bar']
    );
  }

  /**
   * @phpstan-param list<string> $associatedContactPermissions
   * @phpstan-param list<string> $permittedRelationshipTypePermissions
   *
   * @throws \CRM_Core_Exception
   */
  private function addFixtures(array $associatedContactPermissions, array $permittedRelationshipTypePermissions): void {
    $fundingProgramId = FundingProgramFixture::addFixture(['title' => 'Foo'])->getId();
    $this->fundingCaseTypeId = FundingCaseTypeFixture::addFixture()->getId();

    $recipientContactId = ContactFixture::addOrganization([
      'legal_name' => 'Recipient Organization',
    ])['id'];
    $creationContact = ContactFixture::addIndividual(['first_name' => 'creation', 'last_name' => 'contact']);

    $this->permittedFundingCase = FundingCaseFixture::addFixture(
      $fundingProgramId,
      $this->fundingCaseTypeId,
      $recipientContactId,
      $creationContact['id'],
      [
        'creation_date' => '2022-06-23 10:00:00',
        'modification_date' => '2022-06-24 10:00:00',
      ],
    );
    $this->permittedFundingCaseId = $this->permittedFundingCase->getId();

    FundingCaseFixture::addFixture(
      $fundingProgramId,
      $this->fundingCaseTypeId,
      $recipientContactId,
      $creationContact['id'],
      [
        'creation_date' => '2022-06-23 10:00:00',
        'modification_date' => '2022-06-24 10:00:00',
      ],
    );

    $this->applicationProcessId = ApplicationProcessFixture::addFixture(
      $this->permittedFundingCaseId,
      [
        'status' => 'eligible',
        'is_eligible' => TRUE,
      ]
    )->getId();

    $permittedRelationshipTypeId = RelationshipType::create(FALSE)
      ->setValues([
        'name_a_b' => 'permitted',
        'name_b_a' => 'permitted',
        'contact_type_a' => 'Individual',
        'contact_type_b' => 'Individual',
      ])->execute()->first()['id'];

    $notPermittedRelationshipTypeId = RelationshipType::create(FALSE)
      ->setValues([
        'name_b_a' => 'foo',
        'name_a_b' => 'bar',
        'contact_type_a' => 'Individual',
        'contact_type_b' => 'Individual',
      ])->execute()->first()['id'];

    $this->associatedContactIdNoPermissions = ContactFixture::addIndividual([
      'first_name' => 'Associated No Permissions',
      'last_name' => 'User',
    ])['id'];

    $this->associatedContactId = ContactFixture::addIndividual([
      'first_name' => 'Associated',
      'last_name' => 'User',
    ])['id'];

    FundingProgramContactRelationFixture::addContact(
      $this->associatedContactId,
      $fundingProgramId,
      ['view'],
    );

    FundingCaseContactRelationFixture::addContact(
      $this->associatedContactId,
      $this->permittedFundingCaseId,
      $associatedContactPermissions
    );

    FundingProgramContactRelationFixture::addContact(
      $this->associatedContactIdNoPermissions,
      $fundingProgramId,
      ['view'],
    );

    FundingCaseContactRelationFixture::addFixture(
      $this->permittedFundingCaseId,
      'ContactRelationship',
      [
        'contactId' => $this->associatedContactIdNoPermissions,
        'relationshipTypeId' => $permittedRelationshipTypeId,
      ],
      $permittedRelationshipTypePermissions
    );

    $this->relatedABContactId = ContactFixture::addIndividual([
      'first_name' => 'RelatedAB',
      'last_name' => 'User',
    ])['id'];

    Relationship::create(FALSE)
      ->setValues([
        'contact_id_a' => $this->associatedContactIdNoPermissions,
        'contact_id_b' => $this->relatedABContactId,
        'relationship_type_id' => $permittedRelationshipTypeId,
      ])->execute();

    $this->relatedBAContactId = ContactFixture::addIndividual([
      'first_name' => 'RelatedBA',
      'last_name' => 'User',
    ])['id'];

    Relationship::create(FALSE)
      ->setValues([
        'contact_id_a' => $this->relatedBAContactId,
        'contact_id_b' => $this->associatedContactIdNoPermissions,
        'relationship_type_id' => $permittedRelationshipTypeId,
      ])->execute();

    $this->notPermittedContactId = ContactFixture::addIndividual([
      'first_name' => 'NotPermitted',
      'last_name' => 'User',
    ])['id'];

    Relationship::create(FALSE)
      ->setValues([
        'contact_id_a' => $this->notPermittedContactId,
        'contact_id_b' => $this->associatedContactIdNoPermissions,
        'relationship_type_id' => $notPermittedRelationshipTypeId,
      ])->execute();
  }

}
