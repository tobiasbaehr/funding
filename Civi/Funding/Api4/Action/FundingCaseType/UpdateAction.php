<?php
/*
 * Copyright (C) 2024 SYSTOPIA GmbH
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

namespace Civi\Funding\Api4\Action\FundingCaseType;

use Civi\Api4\File;
use Civi\Api4\FundingCaseType;
use Civi\Api4\Generic\DAOUpdateAction;
use Civi\Api4\Generic\Result;
use Civi\Funding\FileTypeNames;
use Civi\Funding\FundingAttachmentManagerInterface;
use Civi\RemoteTools\Api4\Api4Interface;

final class UpdateAction extends DAOUpdateAction {

  private Api4Interface $api4;

  private FundingAttachmentManagerInterface $attachmentManager;

  public function __construct(Api4Interface $api4, FundingAttachmentManagerInterface $attachmentManager) {
    parent::__construct(FundingCaseType::getEntityName(), 'update');
    $this->api4 = $api4;
    $this->attachmentManager = $attachmentManager;
  }

  /**
   * @throws \CRM_Core_Exception
   */
  public function _run(Result $result): void {
    $transferContractTemplateFileId = $this->values['transfer_contract_template_file_id'] ?? NULL;
    unset($this->values['transfer_contract_template_file_id']);

    $paymentInstructionTemplateFileId = $this->values['payment_instruction_template_file_id'] ?? NULL;
    unset($this->values['payment_instruction_template_file_id']);

    $paybackClaimTemplateFileId = $this->values['payback_claim_template_file_id'] ?? NULL;
    unset($this->values['payback_claim_template_file_id']);

    parent::_run($result);

    if (NULL !== $transferContractTemplateFileId) {
      $transferContractTemplateFileId = (int) $transferContractTemplateFileId;
      $this->updateTemplate(
        'transfer_contract_template_file_id',
        $transferContractTemplateFileId,
        FileTypeNames::TRANSFER_CONTRACT_TEMPLATE
      );
      // @phpstan-ignore-next-line
      $result[0]['transfer_contract_template_file_id'] = $transferContractTemplateFileId;
    }

    if (NULL !== $paymentInstructionTemplateFileId) {
      $paymentInstructionTemplateFileId = (int) $paymentInstructionTemplateFileId;
      $this->updateTemplate(
        'payment_instruction_template_file_id',
        $paymentInstructionTemplateFileId,
        FileTypeNames::PAYMENT_INSTRUCTION_TEMPLATE
      );
      // @phpstan-ignore-next-line
      $result[0]['payment_instruction_template_file_id'] = $paymentInstructionTemplateFileId;
    }

    if (NULL !== $paybackClaimTemplateFileId) {
      $paybackClaimTemplateFileId = (int) $paybackClaimTemplateFileId;
      $this->updateTemplate(
        'payback_claim_template_file_id',
        $paybackClaimTemplateFileId,
        FileTypeNames::PAYBACK_CLAIM_TEMPLATE
      );
      // @phpstan-ignore-next-line
      $result[0]['payback_claim_template_file_id'] = $paybackClaimTemplateFileId;
    }
  }

  /**
   * @throws \CRM_Core_Exception
   */
  private function updateTemplate(string $templateIdFieldName, int $templateFileId, string $fileType): void {
    $result = FundingCaseType::get(FALSE)
      ->addSelect('id', $templateIdFieldName)
      ->setWhere($this->getWhere())
      ->execute();

    if ($result->count() > 1) {
      throw new \InvalidArgumentException(
        'Template can only be updated for a single funding case type'
      );
    }

    if ($result->count() === 1) {
      $previousFileId = $result->single()[$templateIdFieldName];
      if ($previousFileId !== NULL && $previousFileId !== $templateFileId) {
        $fundingCaseTypeId = $result->single()['id'];
        $this->attachmentManager->deleteById($previousFileId, 'civicrm_funding_case_type', $fundingCaseTypeId);
      }
    }

    $this->api4->updateEntity(File::getEntityName(), $templateFileId, [
      'file_type_id:name' => $fileType,
    ]);
  }

}
