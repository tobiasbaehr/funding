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

namespace Civi\Funding\Form\SonstigeAktivitaet;

use Civi\Funding\ApplicationProcess\ActionsDeterminer\ApplicationProcessActionsDeterminerInterface;
use Civi\Funding\Contact\FundingCaseRecipientLoaderInterface;
use Civi\Funding\Contact\PossibleRecipientsLoaderInterface;
use Civi\Funding\Entity\ApplicationProcessEntity;
use Civi\Funding\Entity\ApplicationProcessEntityBundle;
use Civi\Funding\Entity\FundingCaseTypeEntity;
use Civi\Funding\Entity\FundingProgramEntity;
use Civi\Funding\Form\ApplicationJsonSchemaFactoryInterface;
use Civi\Funding\Form\JsonSchema\JsonSchemaComment;
use Civi\Funding\Form\SonstigeAktivitaet\JsonSchema\AVK1JsonSchema;
use Civi\Funding\Form\ValidatedApplicationDataInterface;
use Civi\Funding\Form\Validation\ValidationResult;
use Civi\RemoteTools\Form\JsonSchema\JsonSchema;
use Civi\RemoteTools\Form\JsonSchema\JsonSchemaInteger;
use Civi\RemoteTools\Form\JsonSchema\JsonSchemaNull;
use Civi\RemoteTools\Form\JsonSchema\JsonSchemaString;

class AVK1JsonSchemaFactory implements ApplicationJsonSchemaFactoryInterface {

  private ApplicationProcessActionsDeterminerInterface $actionsDeterminer;

  private FundingCaseRecipientLoaderInterface $existingCaseRecipientLoader;

  private PossibleRecipientsLoaderInterface $possibleRecipientsLoader;

  public static function getSupportedFundingCaseTypes(): array {
    return ['AVK1SonstigeAktivitaet'];
  }

  public function __construct(
    ApplicationProcessActionsDeterminerInterface $actionsDeterminer,
    FundingCaseRecipientLoaderInterface $existingCaseRecipientLoader,
    PossibleRecipientsLoaderInterface $possibleRecipientsLoader
  ) {
    $this->actionsDeterminer = $actionsDeterminer;
    $this->existingCaseRecipientLoader = $existingCaseRecipientLoader;
    $this->possibleRecipientsLoader = $possibleRecipientsLoader;
  }

  public function createValidatedData(
    ApplicationProcessEntity $applicationProcess,
    FundingCaseTypeEntity $fundingCaseType,
    ValidationResult $validationResult
  ): ValidatedApplicationDataInterface {
    return new AVK1ValidatedData($validationResult->getData());
  }

  public function createNewValidatedData(
    FundingCaseTypeEntity $fundingCaseType,
    ValidationResult $validationResult
  ): ValidatedApplicationDataInterface {
    return new AVK1ValidatedData($validationResult->getData());
  }

  public function createJsonSchemaExisting(
    ApplicationProcessEntityBundle $applicationProcessBundle
  ): JsonSchema {
    $applicationProcess = $applicationProcessBundle->getApplicationProcess();
    $fundingCase = $applicationProcessBundle->getFundingCase();
    $fundingProgram = $applicationProcessBundle->getFundingProgram();

    $submitActions = $this->actionsDeterminer->getActions(
      $applicationProcess->getFullStatus(),
      $fundingCase->getPermissions()
    );
    if ([] === $submitActions) {
      // empty array is not allowed as enum
      $submitActions = [NULL];
    }
    $extraProperties = [
      'applicationProcessId' => new JsonSchemaInteger(['const' => $applicationProcess->getId(), 'readOnly' => TRUE]),
      'action' => new JsonSchemaString(['enum' => $submitActions]),
    ];
    $extraKeywords = ['required' => array_keys($extraProperties)];

    if ($this->hasReviewPermission($fundingCase->getPermissions())) {
      $extraProperties['comment'] = new JsonSchemaComment();
    }
    else {
      // Prevent adding a comment without permission
      $extraProperties['comment'] = new JsonSchemaNull();
    }

    $jsonSchema = new AVK1JsonSchema(
      $fundingProgram->getRequestsStartDate(),
      $fundingProgram->getRequestsEndDate(),
      $this->existingCaseRecipientLoader->getRecipient($fundingCase),
      $extraProperties,
      $extraKeywords,
    );

    // The readOnly keyword is not inherited, though we use it for informational purposes.
    if (!$this->actionsDeterminer->isEditAllowed(
      $applicationProcess->getFullStatus(),
      $fundingCase->getPermissions()
    )) {
      $jsonSchema->addKeyword('readOnly', TRUE);
    }

    return $jsonSchema;
  }

  public function createJsonSchemaInitial(
    int $contactId,
    FundingCaseTypeEntity $fundingCaseType,
    FundingProgramEntity $fundingProgram
  ): JsonSchema {
    $submitActions = $this->actionsDeterminer->getInitialActions($fundingProgram->getPermissions());
    $extraProperties = [
      'fundingCaseTypeId' => new JsonSchemaInteger(['const' => $fundingCaseType->getId(), 'readOnly' => TRUE]),
      'fundingProgramId' => new JsonSchemaInteger(['const' => $fundingProgram->getId(), 'readOnly' => TRUE]),
      'action' => new JsonSchemaString(['enum' => $submitActions]),
    ];
    $extraKeywords = ['required' => array_keys($extraProperties)];

    return new AVK1JsonSchema(
      $fundingProgram->getRequestsStartDate(),
      $fundingProgram->getRequestsEndDate(),
      $this->possibleRecipientsLoader->getPossibleRecipients($contactId, $fundingProgram),
      $extraProperties,
      $extraKeywords,
    );
  }

  /**
   * @phpstan-param array<string> $permissions
   */
  private function hasReviewPermission(array $permissions): bool {
    return in_array('review_content', $permissions, TRUE)
      || in_array('review_calculative', $permissions, TRUE);
  }

}
