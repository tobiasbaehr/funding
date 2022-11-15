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

namespace Civi\Funding\ApplicationProcess\Command;

use Civi\Funding\Entity\ApplicationProcessEntity;
use Civi\Funding\Entity\FundingCaseEntity;
use Civi\Funding\Entity\FundingCaseTypeEntity;
use Civi\Funding\Entity\FundingProgramEntity;

final class ApplicationFormCreateCommand {

  private ApplicationProcessEntity $applicationProcess;

  private FundingCaseEntity $fundingCase;

  private FundingCaseTypeEntity $fundingCaseType;

  private FundingProgramEntity $fundingProgram;

  /**
   * @phpstan-var array<string, mixed>|null JSON serializable.
   */
  private ?array $data;

  /**
   * @phpstan-param array<string, mixed>|null $data  JSON serializable.
   */
  public function __construct(
    ApplicationProcessEntity $applicationProcess,
    FundingCaseEntity $fundingCase,
    FundingCaseTypeEntity $fundingCaseType,
    FundingProgramEntity $fundingProgram,
    ?array $data = NULL
  ) {
    $this->applicationProcess = $applicationProcess;
    $this->fundingCase = $fundingCase;
    $this->fundingCaseType = $fundingCaseType;
    $this->fundingProgram = $fundingProgram;
    $this->data = $data;
  }

  public function getApplicationProcess(): ApplicationProcessEntity {
    return $this->applicationProcess;
  }

  public function getFundingCase(): FundingCaseEntity {
    return $this->fundingCase;
  }

  public function getFundingCaseType(): FundingCaseTypeEntity {
    return $this->fundingCaseType;
  }

  public function getFundingProgram(): FundingProgramEntity {
    return $this->fundingProgram;
  }

  /**
   * @phpstan-return array<string, mixed>|null JSON serializable.
   */
  public function getData(): ?array {
    return $this->data;
  }

}
