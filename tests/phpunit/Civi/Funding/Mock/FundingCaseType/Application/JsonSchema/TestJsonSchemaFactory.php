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

namespace Civi\Funding\Mock\FundingCaseType\Application\JsonSchema;

use Civi\Funding\Entity\ApplicationProcessEntityBundle;
use Civi\Funding\Entity\FundingCaseEntity;
use Civi\Funding\Entity\FundingCaseTypeEntity;
use Civi\Funding\Entity\FundingProgramEntity;
use Civi\Funding\Form\Application\CombinedApplicationJsonSchemaFactoryInterface;
use Civi\Funding\Form\Application\NonCombinedApplicationJsonSchemaFactoryInterface;
use Civi\Funding\Mock\FundingCaseType\Traits\TestSupportedFundingCaseTypesTrait;
use Civi\Funding\Permission\Traits\HasReviewPermissionTrait;
use Civi\RemoteTools\JsonSchema\JsonSchema;
use Civi\RemoteTools\JsonSchema\JsonSchemaString;

// phpcs:disable Generic.Files.LineLength.TooLong
class TestJsonSchemaFactory implements CombinedApplicationJsonSchemaFactoryInterface, NonCombinedApplicationJsonSchemaFactoryInterface {
// phpcs:enable
  use HasReviewPermissionTrait;

  use TestSupportedFundingCaseTypesTrait;

  /**
   * @inheritDoc
   */
  public function createJsonSchemaAdd(
    FundingProgramEntity $fundingProgram,
    FundingCaseTypeEntity $fundingCaseType,
    FundingCaseEntity $fundingCase
  ): JsonSchema {
    $submitActions = ['save', 'save&new'];
    $extraProperties = [
      '_action' => new JsonSchemaString(['enum' => $submitActions]),
    ];
    $extraKeywords = ['required' => array_keys($extraProperties)];

    return new TestJsonSchema(FALSE, $extraProperties, $extraKeywords);
  }

  public function createJsonSchemaExisting(
    ApplicationProcessEntityBundle $applicationProcessBundle,
    array $applicationProcessStatusList
  ): JsonSchema {
    if ($this->hasReviewPermission($applicationProcessBundle->getFundingCase()->getPermissions())) {
      $submitActions = ['update', 'approve'];
    }
    else {
      $submitActions = ['save', 'withdraw-change'];
    }
    $extraProperties = [
      '_action' => new JsonSchemaString(['enum' => $submitActions]),
    ];
    $extraKeywords = ['required' => array_keys($extraProperties)];

    return new TestJsonSchema(FALSE, $extraProperties, $extraKeywords);
  }

  public function createJsonSchemaInitial(
    int $contactId,
    FundingCaseTypeEntity $fundingCaseType,
    FundingProgramEntity $fundingProgram
  ): JsonSchema {
    $submitActions = ['save'];
    $extraProperties = [
      '_action' => new JsonSchemaString(['enum' => $submitActions]),
    ];
    $extraKeywords = ['required' => array_keys($extraProperties)];

    return new TestJsonSchema(TRUE, $extraProperties, $extraKeywords);
  }

}
