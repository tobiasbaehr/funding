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

namespace Civi\Funding\ApplicationProcess\Handler;

use Civi\Funding\ApplicationProcess\ApplicationProcessManager;
use Civi\Funding\ApplicationProcess\Command\ApplicationFormNewSubmitCommand;
use Civi\Funding\ApplicationProcess\Command\ApplicationFormNewValidateCommand;
use Civi\Funding\ApplicationProcess\StatusDeterminer\ApplicationProcessStatusDeterminerInterface;
use Civi\Funding\EntityFactory\ApplicationProcessFactory;
use Civi\Funding\EntityFactory\FundingCaseFactory;
use Civi\Funding\EntityFactory\FundingCaseTypeFactory;
use Civi\Funding\EntityFactory\FundingProgramFactory;
use Civi\Funding\FundingCase\FundingCaseManager;
use Civi\Funding\Mock\ApplicationProcess\Form\Validation\ApplicationFormValidationResultFactory;
use Civi\Funding\Mock\Form\ValidatedApplicationDataMock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Civi\Funding\ApplicationProcess\Handler\ApplicationFormNewSubmitHandler
 * @covers \Civi\Funding\ApplicationProcess\Command\ApplicationFormNewSubmitCommand
 * @covers \Civi\Funding\ApplicationProcess\Command\ApplicationFormNewSubmitResult
 * @covers \Civi\Funding\ApplicationProcess\Command\AbstractApplicationFormSubmitResult
 */
final class ApplicationFormNewSubmitHandlerTest extends TestCase {

  /**
   * @var \Civi\Funding\ApplicationProcess\ApplicationProcessManager&\PHPUnit\Framework\MockObject\MockObject
   */
  private MockObject $applicationProcessManagerMock;

  /**
   * @var \Civi\Funding\FundingCase\FundingCaseManager&\PHPUnit\Framework\MockObject\MockObject
   */
  private MockObject $fundingCaseManagerMock;

  private ApplicationFormNewSubmitHandler $handler;

  /**
   * @var \Civi\Funding\ApplicationProcess\StatusDeterminer\ApplicationProcessStatusDeterminerInterface&\PHPUnit\Framework\MockObject\MockObject
   */
  private MockObject $statusDeterminerMock;

  /**
   * @var \Civi\Funding\ApplicationProcess\Handler\ApplicationFormNewValidateHandlerInterface&\PHPUnit\Framework\MockObject\MockObject
   */
  private MockObject $validateHandlerMock;

  protected function setUp(): void {
    parent::setUp();
    $this->applicationProcessManagerMock = $this->createMock(ApplicationProcessManager::class);
    $this->fundingCaseManagerMock = $this->createMock(FundingCaseManager::class);
    $this->statusDeterminerMock = $this->createMock(ApplicationProcessStatusDeterminerInterface::class);
    $this->validateHandlerMock = $this->createMock(ApplicationFormNewValidateHandlerInterface::class);
    $this->handler = new ApplicationFormNewSubmitHandler(
      $this->applicationProcessManagerMock,
      $this->fundingCaseManagerMock,
      $this->statusDeterminerMock,
      $this->validateHandlerMock
    );
  }

  public function testHandle(): void {
    $command = $this->createCommand();

    $validationResult = ApplicationFormValidationResultFactory::createValid();
    $this->validateHandlerMock->method('handle')->with(new ApplicationFormNewValidateCommand(
      $command->getFundingProgram(),
      $command->getFundingCaseType(),
      $command->getData()
    ))->willReturn($validationResult);

    $this->statusDeterminerMock->expects(static::once())->method('getInitialStatus')
      ->with(ValidatedApplicationDataMock::ACTION)
      ->willReturn('test_status');

    $fundingCase = FundingCaseFactory::createFundingCase();
    $this->fundingCaseManagerMock->expects(static::once())->method('getOrCreate')
      ->with(['addable_status'], $command->getContactId(), [
        'funding_program' => $command->getFundingProgram(),
        'funding_case_type' => $command->getFundingCaseType(),
        'recipient_contact_id' => ApplicationFormValidationResultFactory::RECIPIENT_CONTACT_ID,
      ])->willReturn($fundingCase);

    $applicationProcess = ApplicationProcessFactory::createApplicationProcess();
    $this->applicationProcessManagerMock->expects(static::once())->method('create')
      ->with(
        $command->getContactId(),
        $fundingCase,
        $command->getFundingCaseType(),
        $command->getFundingProgram(),
        'test_status',
        $validationResult->getValidatedData()
      )->willReturn($applicationProcess);

    $result = $this->handler->handle($command);

    static::assertTrue($result->isSuccess());
    static::assertSame($validationResult, $result->getValidationResult());
    static::assertNotNull($result->getApplicationProcessBundle());
    static::assertSame($applicationProcess, $result->getApplicationProcessBundle()->getApplicationProcess());
  }

  public function testHandleInvalid(): void {
    $command = $this->createCommand();

    $errorMessages = ['/field' => ['error']];
    $validationResult = ApplicationFormValidationResultFactory::createInvalid($errorMessages);
    $this->validateHandlerMock->method('handle')->with(new ApplicationFormNewValidateCommand(
      $command->getFundingProgram(),
      $command->getFundingCaseType(),
      $command->getData()
    ))->willReturn($validationResult);

    $this->applicationProcessManagerMock->expects(static::never())->method('update');

    $result = $this->handler->handle($command);

    static::assertFalse($result->isSuccess());
    static::assertSame($validationResult, $result->getValidationResult());
    static::assertNull($result->getApplicationProcessBundle());
  }

  private function createCommand(): ApplicationFormNewSubmitCommand {
    return new ApplicationFormNewSubmitCommand(
      1,
      FundingCaseTypeFactory::createFundingCaseType([
        'properties' => ['applicationAddableStatusList' => ['addable_status']],
      ]),
      FundingProgramFactory::createFundingProgram(),
      ['test' => 'foo'],
    );
  }

}
