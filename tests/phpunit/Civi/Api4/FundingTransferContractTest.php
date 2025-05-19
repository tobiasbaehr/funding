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

use Civi\Funding\AbstractFundingHeadlessTestCase;
use Civi\Funding\FileTypeNames;
use Civi\Funding\Fixtures\ApplicationProcessFixture;
use Civi\Funding\Fixtures\AttachmentFixture;
use Civi\Funding\Fixtures\ContactFixture;
use Civi\Funding\Fixtures\FundingCaseContactRelationFixture;
use Civi\Funding\Fixtures\FundingCaseFixture;
use Civi\Funding\Fixtures\FundingCaseTypeFixture;
use Civi\Funding\Fixtures\FundingProgramContactRelationFixture;
use Civi\Funding\Fixtures\FundingProgramFixture;
use Civi\Funding\Fixtures\PayoutProcessFixture;
use Civi\Funding\Util\RequestTestUtil;
use CRM_Funding_ExtensionUtil as E;

/**
 * @group headless
 *
 * @covers \Civi\Api4\FundingTransferContract
 * @covers \Civi\Funding\Api4\Action\FundingTransferContract\GetAction
 * @covers \Civi\Funding\Api4\Action\FundingTransferContract\GetFieldsAction
 */
final class FundingTransferContractTest extends AbstractFundingHeadlessTestCase {

  public function testGet(): void {
    $recipientContact = ContactFixture::addOrganization();
    $creationContact = ContactFixture::addIndividual(['first_name' => 'creation', 'last_name' => 'contact']);
    $fundingProgram = FundingProgramFixture::addFixture();
    $fundingCaseType = FundingCaseTypeFixture::addFixture();
    $fundingCase = FundingCaseFixture::addFixture(
      $fundingProgram->getId(),
      $fundingCaseType->getId(),
      $recipientContact['id'],
      $creationContact['id'],
      ['amount_approved' => 12.34],
    );
    $payoutProcess = PayoutProcessFixture::addFixture($fundingCase->getId(), ['amount_total' => 12.34]);
    ApplicationProcessFixture::addFixture($fundingCase->getId(), [
      'identifier' => 'identifier1',
      'title' => 'title1',
      'is_eligible' => TRUE,
    ]);
    ApplicationProcessFixture::addFixture($fundingCase->getId(), [
      'identifier' => 'identifier2',
      'title' => 'title2',
      'is_eligible' => TRUE,
    ]);
    ApplicationProcessFixture::addFixture($fundingCase->getId(), [
      'identifier' => 'identifier3',
      'title' => 'title3',
      'is_eligible' => FALSE,
    ]);
    AttachmentFixture::addFixture(
      'civicrm_funding_case',
      $fundingCase->getId(),
      E::path('tests/phpunit/resources/FundingCaseDocumentTemplate.docx'),
      ['file_type_id:name' => FileTypeNames::TRANSFER_CONTRACT],
    );

    $contact = ContactFixture::addIndividual();
    FundingProgramContactRelationFixture::addContact(
      $contact['id'],
      $fundingProgram->getId(),
      ['view']
    );
    FundingCaseContactRelationFixture::addContact($contact['id'], $fundingCase->getId(), ['review_test']);

    RequestTestUtil::mockInternalRequest($contact['id']);
    $action = FundingTransferContract::get();
    $result = $action->execute();
    static::assertCount(1, $result);

    /** @var array<string, mixed> $values */
    $values = $result->first();
    $expected = [
      'funding_case_id' => $fundingCase->getId(),
      'identifier' => $fundingCase->getIdentifier(),
      'amount_approved' => 12.34,
      'payout_process_id' => $payoutProcess->getId(),
      'amount_paid_out' => 0.0,
      'amount_available' => 12.34,
      'transfer_contract_uri'
      => 'http://localhost/civicrm/funding/transfer-contract/download?fundingCaseId=' . $fundingCase->getId(),
      'funding_case_type_id' => $fundingCaseType->getId(),
      'funding_program_id' => $fundingProgram->getId(),
      'currency' => $fundingProgram->getCurrency(),
      'funding_program_title' => $fundingProgram->getTitle(),
      'CAN_create_drawdown' => FALSE,
      'CAN_view_contract' => FALSE,
    ];
    static::assertEquals($expected, $values);

    $action->setSelect([
      '*',
      'creation_contact_display_name',
      'recipient_contact_display_name',
      'application_process_identifiers',
      'application_process_titles',
    ]);
    $result = $action->execute();
    static::assertCount(1, $result);

    /** @var array<string, mixed> $values */
    $values = $result->first();
    $expected = [
      'funding_case_id' => $fundingCase->getId(),
      'identifier' => $fundingCase->getIdentifier(),
      'amount_approved' => 12.34,
      'payout_process_id' => $payoutProcess->getId(),
      'amount_paid_out' => 0.0,
      'amount_available' => 12.34,
      'transfer_contract_uri'
      => 'http://localhost/civicrm/funding/transfer-contract/download?fundingCaseId=' . $fundingCase->getId(),
      'funding_case_type_id' => $fundingCaseType->getId(),
      'funding_program_id' => $fundingProgram->getId(),
      'currency' => $fundingProgram->getCurrency(),
      'funding_program_title' => $fundingProgram->getTitle(),
      'creation_contact_display_name' => 'creation contact',
      'recipient_contact_display_name' => 'Test organization',
      'application_process_identifiers' => 'identifier1, identifier2',
      'application_process_titles' => 'title1, title2',
      'CAN_create_drawdown' => FALSE,
      'CAN_view_contract' => FALSE,
    ];
    static::assertEquals($expected, $values);

    // Add second funding case.
    $fundingCase2 = FundingCaseFixture::addFixture(
      $fundingProgram->getId(),
      $fundingCaseType->getId(),
      $recipientContact['id'],
      $creationContact['id'],
      ['amount_approved' => 12.34],
    );
    $payoutProcess2 = PayoutProcessFixture::addFixture($fundingCase2->getId(), ['amount_total' => 12.34]);
    $result = $action->execute();
    static::assertCount(1, $result);

    // Permission to access second funding case.
    FundingCaseContactRelationFixture::addContact($contact['id'], $fundingCase2->getId(), ['review_test']);
    $result = $action->execute();
    static::assertCount(2, $result);

    // Select second funding case only.
    $action->addWhere('funding_case_id', '=', $fundingCase2->getId());
    $result = $action->execute();
    static::assertCount(1, $result);
    static::assertSame($fundingCase2->getId(), $result->first()['funding_case_id']);
  }

  public function testGetFields(): void {
    $action = FundingTransferContract::getFields();
    $result = $action->execute();

    /** @phpstan-var array<string, mixed> $field */
    foreach ($result as $field) {
      static::assertIsString($field['name']);
      static::assertNotEmpty($field['name']);
      $message = sprintf('Failed for field %s', $field['name']);
      static::assertNotEmpty($field['data_type'], $message);
      static::assertTrue($field['readonly'], $message);
    }

    static::assertCount(23, $result);
  }

}
