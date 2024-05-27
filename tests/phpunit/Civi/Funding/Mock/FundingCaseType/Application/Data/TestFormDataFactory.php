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

namespace Civi\Funding\Mock\FundingCaseType\Application\Data;

use Civi\Funding\ApplicationProcess\ApplicationExternalFileManagerInterface;
use Civi\Funding\Entity\ApplicationProcessEntity;
use Civi\Funding\Entity\FundingCaseEntity;
use Civi\Funding\Form\Application\ApplicationFormDataFactoryInterface;
use Civi\Funding\Mock\FundingCaseType\Traits\TestSupportedFundingCaseTypesTrait;
use Webmozart\Assert\Assert;

final class TestFormDataFactory implements ApplicationFormDataFactoryInterface {

  use TestSupportedFundingCaseTypesTrait;

  private ApplicationExternalFileManagerInterface $externalFileManager;

  public function __construct(ApplicationExternalFileManagerInterface $externalFileManager) {
    $this->externalFileManager = $externalFileManager;
  }

  /**
   * @inheritDoc
   */
  public function createFormData(ApplicationProcessEntity $applicationProcess, FundingCaseEntity $fundingCase): array {
    Assert::notNull($applicationProcess->getStartDate());
    Assert::notNull($applicationProcess->getEndDate());

    $data = [
      'title' => $applicationProcess->getTitle(),
      'shortDescription' => $applicationProcess->getShortDescription(),
      'recipient' => $fundingCase->getRecipientContactId(),
      'startDate' => $applicationProcess->getStartDate()->format('Y-m-d'),
      'endDate' => $applicationProcess->getEndDate()->format('Y-m-d'),
    ];

    /** @var \Civi\Funding\Entity\ExternalFileEntity $file */
    $file = $this->externalFileManager->getFile('file', $applicationProcess->getId());
    $data['file'] = $file->getUri();

    return $data;
  }

  /**
   * @inheritDoc
   */
  public function createFormDataForCopy(
    ApplicationProcessEntity $applicationProcess,
    FundingCaseEntity $fundingCase
  ): array {
    return $this->createFormData($applicationProcess, $fundingCase);
  }

}
