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

namespace Civi\Funding;

use Civi\Api4\FundingCaseType;
use Civi\Api4\FundingProgram;
use Civi\Funding\Database\DaoEntityInfoProvider;
use Civi\Funding\Fixtures\FundingCaseTypeFixture;
use Civi\Funding\Fixtures\FundingProgramFixture;
use Civi\RemoteTools\Api4\Api4;
use Civi\RemoteTools\Api4\Query\Comparison;

/**
 * @covers \Civi\Funding\FundingExternalFileManager
 *
 * @group headless
 */
final class FundingExternalFileManagerTest extends AbstractFundingHeadlessTestCase {

  /**
   * @var \Civi\Funding\FundingExternalFileManager
   */
  private FundingExternalFileManager $externalFileManager;

  protected function setUp(): void {
    parent::setUp();
    $this->externalFileManager = new FundingExternalFileManager(new Api4(), new DaoEntityInfoProvider());
  }

  public function test(): void {
    $fundingProgram = FundingProgramFixture::addFixture();
    $fundingCaseType = FundingCaseTypeFixture::addFixture();

    $externalFile1 = $this->externalFileManager->addFile(
      'https://example.org/test1.txt',
      'identifier1',
      FundingProgram::getEntityName(),
      $fundingProgram->getId(),
    );

    static::assertGreaterThan(0, $externalFile1->getId());
    static::assertSame('https://example.org/test1.txt', $externalFile1->getSource());
    static::assertSame('identifier1', $externalFile1->getIdentifier());
    static::assertStringStartsWith('http://', $externalFile1->getUri());
    static::assertSame([
      'entityName' => FundingProgram::getEntityName(),
      'entityId' => $fundingProgram->getId(),
    ], $externalFile1->getCustomData());

    static::assertEquals(
      $externalFile1,
      $this->externalFileManager->getFile(
        'identifier1',
        FundingProgram::getEntityName(),
        $fundingProgram->getId()
      )
    );

    static::assertNull(
      $this->externalFileManager->getFile(
        '99112233-4455-6677-8899-aabbccddeeff',
        FundingProgram::getEntityName(),
        $fundingProgram->getId()
      )
    );

    static::assertNull(
      $this->externalFileManager->getFile(
        'identifier1',
        FundingProgram::getEntityName(),
        $fundingProgram->getId() + 1
      )
    );

    static::assertEquals(
      [$externalFile1],
      $this->externalFileManager->getFiles(
        FundingProgram::getEntityName(),
        $fundingProgram->getId()
      )
    );

    static::assertFalse(
      $this->externalFileManager->isAttachedToEntityType($externalFile1, FundingCaseType::getEntityName())
    );
    $this->externalFileManager->attachFile($externalFile1, FundingCaseType::getEntityName(), $fundingCaseType->getId());
    static::assertTrue(
      $this->externalFileManager->isAttachedToEntityType($externalFile1, FundingCaseType::getEntityName())
    );
    $this->externalFileManager->detachFile($externalFile1, FundingCaseType::getEntityName(), $fundingCaseType->getId());
    static::assertFalse(
      $this->externalFileManager->isAttachedToEntityType($externalFile1, FundingCaseType::getEntityName())
    );

    $this->externalFileManager->updateCustomData($externalFile1, ['foo' => 'bar']);
    static::assertSame([
      'foo' => 'bar',
      'entityName' => FundingProgram::getEntityName(),
      'entityId' => $fundingProgram->getId(),
    ], $externalFile1->getCustomData());
    static::assertEquals(
      $externalFile1,
      $this->externalFileManager->getFile(
        'identifier1',
        FundingProgram::getEntityName(),
        $fundingProgram->getId()
      )
    );

    $this->externalFileManager->updateIdentifier($externalFile1, 'newIdentifier1');
    static::assertSame('newIdentifier1', $externalFile1->getIdentifier());
    static::assertEquals(
      $externalFile1,
      $this->externalFileManager->getFile(
        'newIdentifier1',
        FundingProgram::getEntityName(),
        $fundingProgram->getId()
      )
    );

    $externalFile2 = $this->externalFileManager->addFile(
      'https://example.org/test2.txt',
      'identifier2',
      FundingProgram::getEntityName(),
      $fundingProgram->getId(),
    );
    static::assertEquals(
      [$externalFile1, $externalFile2],
      $this->externalFileManager->getFiles(
        FundingProgram::getEntityName(),
        $fundingProgram->getId()
      )
    );
    static::assertEquals(
      [$externalFile2],
      $this->externalFileManager->getFiles(
        FundingProgram::getEntityName(),
        $fundingProgram->getId(),
        Comparison::new('source', '=', $externalFile2->getSource())
      )
    );

    $this->externalFileManager->deleteFile($externalFile1);
    static::assertNull(
      $this->externalFileManager->getFile(
        'identifier1',
        FundingProgram::getEntityName(),
        $fundingProgram->getId()
      )
    );

    $this->externalFileManager->deleteFiles(
      FundingProgram::getEntityName(),
      $fundingProgram->getId(),
      ['identifier2'],
    );
    static::assertEquals(
      [$externalFile2],
      $this->externalFileManager->getFiles(
        FundingProgram::getEntityName(),
        $fundingProgram->getId()
      )
    );

    $this->externalFileManager->deleteFiles(
      FundingProgram::getEntityName(),
      $fundingProgram->getId(),
      ['excludedIdentifier'],
    );
    static::assertEquals(
      [],
      $this->externalFileManager->getFiles(
        FundingProgram::getEntityName(),
        $fundingProgram->getId()
      )
    );
  }

}
