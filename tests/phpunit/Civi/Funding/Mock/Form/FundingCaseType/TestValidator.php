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

namespace Civi\Funding\Mock\Form\FundingCaseType;

use Civi\Funding\Entity\ApplicationProcessEntityBundle;
use Civi\Funding\Entity\FundingCaseEntity;
use Civi\Funding\Entity\FundingCaseTypeEntity;
use Civi\Funding\Entity\FundingProgramEntity;
use Civi\Funding\Form\AbstractSummaryApplicationValidator;
use Civi\Funding\Form\ApplicationValidationResult;
use Civi\Funding\Form\NonSummaryApplicationValidatorInterface;
use Civi\Funding\Form\ValidatedApplicationDataInvalid;
use Civi\Funding\Mock\Form\FundingCaseType\Traits\TestSupportedFundingCaseTypesTrait;
use Civi\RemoteTools\JsonSchema\JsonSchema;

// phpcs:disable Generic.Files.LineLength.TooLong
/**
 * @property \Civi\Funding\Form\SummaryApplicationJsonSchemaFactoryInterface&\Civi\Funding\Form\NonSummaryApplicationJsonSchemaFactoryInterface $jsonSchemaFactory
 */
final class TestValidator extends AbstractSummaryApplicationValidator implements NonSummaryApplicationValidatorInterface {
// phpcs:enable
  use TestSupportedFundingCaseTypesTrait;

  /**
   * @inheritDoc
   */
  public function validateInitial(
    int $contactId,
    FundingProgramEntity $fundingProgram,
    FundingCaseTypeEntity $fundingCaseType,
    array $data,
    int $maxErrors = 1
  ): ApplicationValidationResult {
    $jsonSchema = $this->jsonSchemaFactory->createJsonSchemaInitial($contactId, $fundingCaseType, $fundingProgram);
    $jsonSchemaValidationResult = $this->jsonSchemaValidator->validate($jsonSchema, $data, $maxErrors);
    if (!$jsonSchemaValidationResult->isValid()) {
      return ApplicationValidationResult::newInvalid(
      // @phpstan-ignore-next-line leaf error messages are not empty.
        $jsonSchemaValidationResult->getLeafErrorMessages(),
        new ValidatedApplicationDataInvalid($jsonSchemaValidationResult->getData()),
      );
    }

    return $this->getValidationResultInitial(
      $contactId,
      $fundingProgram,
      $fundingCaseType,
      $data,
      $jsonSchema,
      $jsonSchemaValidationResult->getData(),
      $maxErrors,
    );
  }

  /**
   * @inheritDoc
   */
  protected function getValidationResultAdd(
    FundingProgramEntity $fundingProgram,
    FundingCaseTypeEntity $fundingCaseType,
    FundingCaseEntity $fundingCase,
    array $formData,
    JsonSchema $jsonSchema,
    array $validatedData,
    int $maxErrors
  ): ApplicationValidationResult {
    return $this->createValidationResultValid(new TestValidatedData($validatedData), $jsonSchema);
  }

  /**
   * @inheritDoc
   */
  protected function getValidationResultExisting(
    ApplicationProcessEntityBundle $applicationProcessBundle,
    array $formData,
    JsonSchema $jsonSchema,
    array $validatedData,
    int $maxErrors
  ): ApplicationValidationResult {
    return $this->createValidationResultValid(new TestValidatedData($validatedData), $jsonSchema);
  }

  /**
   * Called after successful JSON schema validation.
   *
   * @phpstan-param array<string, mixed> $formData JSON serializable.
   * @phpstan-param array<string, mixed> $validatedData JSON serializable.
   *   Data returned by JSON schema validator.
   */
  protected function getValidationResultInitial(
    int $contactId,
    FundingProgramEntity $fundingProgram,
    FundingCaseTypeEntity $fundingCaseType,
    array $formData,
    JsonSchema $jsonSchema,
    array $validatedData,
    int $maxErrors
  ): ApplicationValidationResult {
    return $this->createValidationResultValid(new TestValidatedData($validatedData), $jsonSchema);
  }

}
