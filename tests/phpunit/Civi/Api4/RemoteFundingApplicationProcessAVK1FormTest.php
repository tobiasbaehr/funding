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

use Civi\Funding\AbstractRemoteFundingHeadlessTestCase;
use Civi\Funding\Entity\ApplicationProcessEntity;
use Civi\Funding\Entity\FundingCaseEntity;
use Civi\Funding\Entity\FundingCaseTypeEntity;
use Civi\Funding\Entity\FundingProgramEntity;
use Civi\Funding\Fixtures\ApplicationProcessFixture;
use Civi\Funding\Fixtures\ContactFixture;
use Civi\Funding\Fixtures\FundingCaseContactRelationFixture;
use Civi\Funding\Fixtures\FundingCaseFixture;
use Civi\Funding\Fixtures\FundingCaseTypeFixture;
use Civi\Funding\Fixtures\FundingCaseTypeProgramFixture;
use Civi\Funding\Fixtures\FundingProgramContactRelationFixture;
use Civi\Funding\Fixtures\FundingProgramFixture;

/**
 * @group headless
 *
 * @covers \Civi\Api4\RemoteFundingApplicationProcess
 */
final class RemoteFundingApplicationProcessAVK1FormTest extends AbstractRemoteFundingHeadlessTestCase {

  private ApplicationProcessEntity $applicationProcess;

  private FundingCaseEntity $fundingCase;

  private FundingCaseTypeEntity $fundingCaseType;

  private FundingProgramEntity $fundingProgram;

  /**
   * @phpstan-var array<string, mixed>&array{id: int}
   */
  private array $contact;

  protected function setUp(): void {
    parent::setUp();
    $this->addFixtures();
  }

  public function testGetForm(): void {
    $action = RemoteFundingApplicationProcess::getForm()
      ->setRemoteContactId((string) $this->contact['id'])
      ->setApplicationProcessId($this->applicationProcess->getId());

    $e = NULL;
    try {
      $action->execute();
    }
    catch (\Exception $e) {
      // @ignoreException
    }
    static::assertNotNull($e);
    static::assertSame(
      sprintf('Application process with ID "%d" not found', $this->applicationProcess->getId()),
      $e->getMessage()
    );

    FundingCaseContactRelationFixture::addContact(
      $this->contact['id'],
      $this->fundingCase->getId(),
      ['application_permission'],
    );
    $this->clearCache();

    $values = $action->execute()->getArrayCopy();
    static::assertEquals(['jsonSchema', 'uiSchema', 'data'], array_keys($values));
    static::assertIsArray($values['jsonSchema']);
    static::assertSame('object', $values['jsonSchema']['properties']['grunddaten']['type']);
    static::assertIsArray($values['uiSchema']);
    static::assertSame('Förderantrag für Sonstige Aktivitäten (SoA) / Virtuelle Kurse', $values['uiSchema']['label']);
    static::assertTrue($values['uiSchema']['options']['readonly']);
    static::assertIsArray($values['data']);
    static::assertEquals(
      [
        'titel' => $this->applicationProcess->getTitle(),
        'kurzbeschreibungDesInhalts' => $this->applicationProcess->getShortDescription(),
        'foo' => 'bar',
      ],
      $values['data']['grunddaten']
    );

    FundingCaseContactRelationFixture::addContact(
      $this->contact['id'],
      $this->fundingCase->getId(),
      ['application_modify'],
    );

    $values = $action->execute()->getArrayCopy();
    static::assertEquals(['jsonSchema', 'uiSchema', 'data'], array_keys($values));
    static::assertIsArray($values['jsonSchema']);
    static::assertSame('object', $values['jsonSchema']['properties']['grunddaten']['type']);
    static::assertIsArray($values['uiSchema']);
    static::assertSame('Förderantrag für Sonstige Aktivitäten (SoA) / Virtuelle Kurse', $values['uiSchema']['label']);
    static::assertFalse($values['uiSchema']['options']['readonly'] ?? FALSE);
    static::assertIsArray($values['data']);
    static::assertSame($this->applicationProcess->getTitle(), $values['data']['grunddaten']['titel']);
  }

  public function testValidateForm(): void {
    $action = RemoteFundingApplicationProcess::validateForm()
      ->setRemoteContactId((string) $this->contact['id'])
      ->setApplicationProcessId($this->applicationProcess->getId())
      ->setData([
        'y' => 'z',
      ]);

    $e = NULL;
    try {
      $action->execute();
    }
    catch (\Exception $e) {
      // @ignoreException
    }
    static::assertNotNull($e);
    static::assertSame(
      sprintf('Application process with ID "%d" not found', $this->applicationProcess->getId()),
      $e->getMessage()
    );

    FundingCaseContactRelationFixture::addContact(
      $this->contact['id'],
      $this->fundingCase->getId(),
      ['application_modify'],
    );
    $this->clearCache();

    $values = $action->execute()->getArrayCopy();
    static::assertEquals(['valid', 'errors'], array_keys($values));
    static::assertFalse($values['valid']);
    static::assertNotCount(0, $values['errors']);
  }

  public function testSubmitForm(): void {
    $action = RemoteFundingApplicationProcess::submitForm()
      ->setRemoteContactId((string) $this->contact['id'])
      ->setApplicationProcessId($this->applicationProcess->getId())
      ->setData([
        'y' => 'z',
      ]);

    $e = NULL;
    try {
      // Test without permission
      $action->execute();
    }
    catch (\Exception $e) {
      // @ignoreException
    }
    static::assertNotNull($e);
    static::assertSame(
      sprintf('Application process with ID "%d" not found', $this->applicationProcess->getId()),
      $e->getMessage()
    );

    FundingCaseContactRelationFixture::addContact(
      $this->contact['id'],
      $this->fundingCase->getId(),
      ['application_modify'],
    );
    $this->clearCache();

    // Test with invalid data
    $values = $action->execute()->getArrayCopy();
    static::assertEquals(['action', 'message', 'errors'], array_keys($values));
    static::assertSame('showValidation', $values['action']);
    static::assertSame('Validation failed', $values['message']);
    static::assertNotCount(0, $values['errors']);
  }

  private function addFixtures(): void {
    $this->fundingCaseType = FundingCaseTypeFixture::addFixture([
      'title' => 'AVK1 Test',
      'name' => 'AVK1SonstigeAktivitaet',
    ]);

    $this->fundingProgram = FundingProgramFixture::addFixture([
      'start_date' => date('Y-m-d', time() - 86400),
      'end_date' => date('Y-m-d', time() + 86400),
      'requests_start_date' => date('Y-m-d', time() - 86400),
      'requests_end_date' => date('Y-m-d', time() + 86400),
    ]);

    FundingCaseTypeProgramFixture::addFixture($this->fundingCaseType->getId(), $this->fundingProgram->getId());

    $this->contact = ContactFixture::addIndividual();

    FundingProgramContactRelationFixture::addContact(
      $this->contact['id'],
      $this->fundingProgram->getId(),
      ['application_create']
    );

    $this->fundingCase = FundingCaseFixture::addFixture(
      $this->fundingProgram->getId(),
      $this->fundingCaseType->getId(),
      $this->contact['id'],
      $this->contact['id'],
    );

    $startDate = date('Y-m-d', time() - 86400);
    $endDate = date('Y-m-d', time() + 86400);
    $this->applicationProcess = ApplicationProcessFixture::addFixture(
      $this->fundingCase->getId(),
      [
        'start_date' => $startDate,
        'end_date' => $endDate,
        'request_data' => [
          'grunddaten' => [
            'foo' => 'bar',
          ],
        ],
      ]
    );
  }

}
