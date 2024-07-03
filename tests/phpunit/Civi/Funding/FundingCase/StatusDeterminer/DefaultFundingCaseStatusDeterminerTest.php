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

namespace Civi\Funding\FundingCase\StatusDeterminer;

use Civi\Funding\ApplicationProcess\ActionStatusInfo\ApplicationProcessActionStatusInfoInterface;
use Civi\Funding\ApplicationProcess\ApplicationProcessManager;
use Civi\Funding\EntityFactory\ApplicationProcessBundleFactory;
use Civi\RemoteTools\Api4\Query\Comparison;
use Civi\RemoteTools\Api4\Query\CompositeCondition;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Civi\Funding\FundingCase\StatusDeterminer\DefaultFundingCaseStatusDeterminer
 */
final class DefaultFundingCaseStatusDeterminerTest extends TestCase {

  /**
   * @var \Civi\Funding\ApplicationProcess\ApplicationProcessManager|(\Civi\Funding\ApplicationProcess\ApplicationProcessManager&\PHPUnit\Framework\MockObject\MockObject)|\PHPUnit\Framework\MockObject\MockObject
   */
  private MockObject $applicationProcessManagerMock;

  /**
   * @var \PHPUnit\Framework\MockObject\MockObject|\Civi\Funding\ApplicationProcess\ActionStatusInfo\ApplicationProcessActionStatusInfoInterface|(\Civi\Funding\ApplicationProcess\ActionStatusInfo\ApplicationProcessActionStatusInfoInterface&\PHPUnit\Framework\MockObject\MockObject)
   */
  private MockObject $infoMock;

  private DefaultFundingCaseStatusDeterminer $statusDeterminer;

  protected function setUp(): void {
    parent::setUp();
    $this->applicationProcessManagerMock = $this->createMock(ApplicationProcessManager::class);
    $this->infoMock = $this->createMock(ApplicationProcessActionStatusInfoInterface::class);
    $this->statusDeterminer = new DefaultFundingCaseStatusDeterminer(
      $this->applicationProcessManagerMock,
      $this->infoMock,
    );
  }

  public function testGetStatus(): void {
    static::assertSame('test', $this->statusDeterminer->getStatus('test', 'do_something'));
    static::assertSame('ongoing', $this->statusDeterminer->getStatus('test', 'approve'));
  }

  public function testGetStatusOnApplicationProcessStatusChangeWithdrawn(): void {
    $applicationProcessBundle = ApplicationProcessBundleFactory::createApplicationProcessBundle(['status' => 'sealed']);
    $this->infoMock->method('getFinalIneligibleStatusList')->willReturn(['sealed', 'also_sealed']);
    $this->applicationProcessManagerMock->method('countBy')
      ->with(CompositeCondition::new('AND',
        Comparison::new('funding_case_id', '=', $applicationProcessBundle->getFundingCase()->getId()),
        Comparison::new('status', 'NOT IN', ['sealed', 'also_sealed']),
      ))->willReturn(0);
    $this->infoMock->method('isWithdrawnStatus')
      ->with('sealed')
      ->willReturn(TRUE);

    static::assertSame(
      'withdrawn',
      $this->statusDeterminer->getStatusOnApplicationProcessStatusChange($applicationProcessBundle, 'previous')
    );
  }

  public function testGetStatusOnApplicationProcessStatusChangeRejected(): void {
    $applicationProcessBundle = ApplicationProcessBundleFactory::createApplicationProcessBundle(['status' => 'sealed']);
    $this->infoMock->method('getFinalIneligibleStatusList')->willReturn(['sealed', 'also_sealed']);
    $this->applicationProcessManagerMock->method('countBy')
      ->with(CompositeCondition::new('AND',
        Comparison::new('funding_case_id', '=', $applicationProcessBundle->getFundingCase()->getId()),
        Comparison::new('status', 'NOT IN', ['sealed', 'also_sealed']),
      ))->willReturn(0);
    $this->infoMock->method('isWithdrawnStatus')
      ->with('sealed')
      ->willReturn(FALSE);

    static::assertSame(
      'rejected',
      $this->statusDeterminer->getStatusOnApplicationProcessStatusChange($applicationProcessBundle, 'previous')
    );
  }

  public function testIsClosedByApplicationProcessWithRemainingApplications(): void {
    $applicationProcessBundle = ApplicationProcessBundleFactory::createApplicationProcessBundle(
      ['status' => 'sealed'],
      ['status' => 'test']
    );
    $this->infoMock->method('getFinalIneligibleStatusList')->willReturn(['sealed', 'also_sealed']);
    $this->applicationProcessManagerMock->method('countBy')
      ->with(CompositeCondition::new('AND',
        Comparison::new('funding_case_id', '=', $applicationProcessBundle->getFundingCase()->getId()),
        Comparison::new('status', 'NOT IN', ['sealed', 'also_sealed']),
      ))->willReturn(1);

    static::assertSame(
      'test',
      $this->statusDeterminer->getStatusOnApplicationProcessStatusChange($applicationProcessBundle, 'previous')
    );
  }

  public function testIsClosedByApplicationProcessUnsealedStatus(): void {
    $applicationProcessBundle = ApplicationProcessBundleFactory::createApplicationProcessBundle(
      ['status' => 'unsealed'],
      ['status' => 'test']
    );
    $this->infoMock->method('getFinalIneligibleStatusList')->willReturn(['sealed', 'also_sealed']);
    $this->applicationProcessManagerMock->expects(static::never())->method('countBy');

    static::assertSame(
      'test',
      $this->statusDeterminer->getStatusOnApplicationProcessStatusChange($applicationProcessBundle, 'previous')
    );
  }

}
