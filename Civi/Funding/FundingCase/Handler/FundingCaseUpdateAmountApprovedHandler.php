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

namespace Civi\Funding\FundingCase\Handler;

use Civi\API\Exception\UnauthorizedException;
use Civi\Funding\FundingCase\Actions\FundingCaseActions;
use Civi\Funding\FundingCase\Actions\FundingCaseActionsDeterminerInterface;
use Civi\Funding\FundingCase\Command\FundingCaseUpdateAmountApprovedCommand;
use Civi\Funding\FundingCase\FundingCaseManager;
use Civi\Funding\FundingCase\StatusDeterminer\FundingCaseStatusDeterminerInterface;
use Civi\Funding\TransferContract\TransferContractCreator;
use CRM_Funding_ExtensionUtil as E;

final class FundingCaseUpdateAmountApprovedHandler implements FundingCaseUpdateAmountApprovedHandlerInterface {

  private FundingCaseActionsDeterminerInterface $actionsDeterminer;

  private FundingCaseManager $fundingCaseManager;

  private FundingCaseStatusDeterminerInterface $statusDeterminer;

  private TransferContractCreator $transferContractCreator;

  public function __construct(
    FundingCaseActionsDeterminerInterface $actionsDeterminer,
    FundingCaseManager $fundingCaseManager,
    FundingCaseStatusDeterminerInterface $statusDeterminer,
    TransferContractCreator $transferContractCreator
  ) {
    $this->actionsDeterminer = $actionsDeterminer;
    $this->fundingCaseManager = $fundingCaseManager;
    $this->statusDeterminer = $statusDeterminer;
    $this->transferContractCreator = $transferContractCreator;
  }

  /**
   * @throws \Civi\Funding\Exception\FundingException
   * @throws \CRM_Core_Exception
   */
  public function handle(FundingCaseUpdateAmountApprovedCommand $command): void {
    $fundingCase = $command->getFundingCase();
    $this->assertAuthorized($command);

    $fundingCase->setAmountApproved($command->getAmount());

    $fundingCase->setStatus($this->statusDeterminer->getStatus(
      $fundingCase->getStatus(),
      FundingCaseActions::UPDATE_AMOUNT_APPROVED
    ));

    $this->transferContractCreator->createTransferContract(
      $fundingCase,
      $command->getFundingCaseType(),
      $command->getFundingProgram(),
    );

    $this->fundingCaseManager->update($fundingCase);
  }

  /**
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  private function assertAuthorized(FundingCaseUpdateAmountApprovedCommand $command): void {
    if (!$command->isAuthorized() && !$this->actionsDeterminer->isActionAllowed(
      FundingCaseActions::UPDATE_AMOUNT_APPROVED,
      $command->getFundingCase()->getStatus(),
      $command->getApplicationProcessStatusList(),
      $command->getFundingCase()->getPermissions(),
    )) {
      throw new UnauthorizedException(E::ts('Updating the approved amount of this funding case is not allowed.'));
    }
  }

}
