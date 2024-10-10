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

namespace Civi\Funding\PayoutProcess;

use Civi\Funding\Entity\DrawdownEntity;
use Civi\Funding\Exception\FundingException;
use Civi\Funding\FileTypeNames;
use Civi\Funding\FundingAttachmentManagerInterface;
use Civi\Funding\FundingCase\FundingCaseManager;
use Civi\Funding\FundingProgram\FundingCaseTypeManager;
use Civi\Funding\FundingProgram\FundingProgramManager;
use Civi\Funding\PayoutProcess\Command\DrawdownDocumentRenderCommand;
use Civi\Funding\PayoutProcess\Handler\DrawdownDocumentRenderHandlerInterface;
use CRM_Funding_ExtensionUtil as E;
use Webmozart\Assert\Assert;

class DrawdownDocumentCreator {

  private FundingAttachmentManagerInterface $attachmentManager;

  private BankAccountManager $bankAccountManager;

  private FundingCaseManager $fundingCaseManager;

  private FundingCaseTypeManager $fundingCaseTypeManager;

  private FundingProgramManager $fundingProgramManager;

  private DrawdownDocumentRenderHandlerInterface $drawdownDocumentRenderHandler;

  private PayoutProcessManager $payoutProcessManager;

  public function __construct(
    FundingAttachmentManagerInterface $attachmentManager,
    BankAccountManager $bankAccountManager,
    FundingCaseManager $fundingCaseManager,
    FundingCaseTypeManager $fundingCaseTypeManager,
    FundingProgramManager $fundingProgramManager,
    DrawdownDocumentRenderHandlerInterface $drawdownDocumentRenderHandler,
    PayoutProcessManager $payoutProcessManager
  ) {
    $this->attachmentManager = $attachmentManager;
    $this->bankAccountManager = $bankAccountManager;
    $this->fundingCaseManager = $fundingCaseManager;
    $this->fundingCaseTypeManager = $fundingCaseTypeManager;
    $this->fundingProgramManager = $fundingProgramManager;
    $this->drawdownDocumentRenderHandler = $drawdownDocumentRenderHandler;
    $this->payoutProcessManager = $payoutProcessManager;
  }

  /**
   * @throws \Civi\Funding\Exception\FundingException
   * @throws \CRM_Core_Exception
   */
  public function createDrawdownDocument(DrawdownEntity $drawdown): void {
    $payoutProcess = $this->payoutProcessManager->get($drawdown->getPayoutProcessId());
    Assert::notNull($payoutProcess);
    $fundingCase = $this->fundingCaseManager->get($payoutProcess->getFundingCaseId());
    Assert::notNull($fundingCase);
    $bankAccount = $this->bankAccountManager->getBankAccountByContactId($fundingCase->getRecipientContactId());
    if (NULL === $bankAccount) {
      throw new FundingException(E::ts(
        'No bank account for contact with ID "%1" available.',
        [1 => $fundingCase->getRecipientContactId()]
      ));
    }

    $fundingCaseType = $this->fundingCaseTypeManager->get($fundingCase->getFundingCaseTypeId());
    Assert::notNull($fundingCaseType);
    $fundingProgram = $this->fundingProgramManager->get($fundingCase->getFundingProgramId());
    Assert::notNull($fundingProgram);
    $command = new DrawdownDocumentRenderCommand(
      $drawdown,
      $bankAccount,
      $payoutProcess,
      $fundingCase,
      $fundingCaseType,
      $fundingProgram,
    );
    $result = $this->drawdownDocumentRenderHandler->handle($command);

    // There might be only one payment instruction for a drawdown.
    $this->attachmentManager->attachFileUniqueByFileType(
      'civicrm_funding_drawdown',
      $drawdown->getId(),
      $drawdown->getAmount() < 0 ? FileTypeNames::PAYBACK_CLAIM : FileTypeNames::PAYMENT_INSTRUCTION,
      $result->getFilename(),
      $result->getMimeType(),
      [
        'name' => sprintf(
          $drawdown->getAmount() < 0 ? 'payback-claim.%d.%s' : 'payment-instruction.%d.%s',
          $drawdown->getId(),
          pathinfo($result->getFilename(), PATHINFO_EXTENSION)
        ),
      ],
    );
  }

}
