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

namespace Civi\Funding;

use Civi\Funding\ApplicationProcess\Handler\ApplicationActionApplyHandlerInterface;
use Civi\Funding\ApplicationProcess\Handler\ApplicationAllowedActionsGetHandlerInterface;
use Civi\Funding\ApplicationProcess\Handler\ApplicationCostItemsPersistHandlerInterface;
use Civi\Funding\ApplicationProcess\Handler\ApplicationDeleteHandlerInterface;
use Civi\Funding\ApplicationProcess\Handler\ApplicationFilesAddIdentifiersHandlerInterface;
use Civi\Funding\ApplicationProcess\Handler\ApplicationFilesPersistHandlerInterface;
use Civi\Funding\ApplicationProcess\Handler\ApplicationFormAddCreateHandlerInterface;
use Civi\Funding\ApplicationProcess\Handler\ApplicationFormAddSubmitHandlerInterface;
use Civi\Funding\ApplicationProcess\Handler\ApplicationFormCreateHandlerInterface;
use Civi\Funding\ApplicationProcess\Handler\ApplicationFormDataGetHandlerInterface;
use Civi\Funding\ApplicationProcess\Handler\ApplicationFormNewCreateHandlerInterface;
use Civi\Funding\ApplicationProcess\Handler\ApplicationFormNewSubmitHandlerInterface;
use Civi\Funding\ApplicationProcess\Handler\ApplicationFormSubmitHandlerInterface;
use Civi\Funding\ApplicationProcess\Handler\ApplicationJsonSchemaGetHandlerInterface;
use Civi\Funding\ApplicationProcess\Handler\ApplicationResourcesItemsPersistHandlerInterface;
use Civi\Funding\ApplicationProcess\Handler\ApplicationSnapshotCreateHandlerInterface;
use Civi\Funding\ApplicationProcess\StatusDeterminer\ApplicationProcessStatusDeterminerInterface;
use Civi\Funding\FundingCase\Handler\FundingCaseApproveHandlerInterface;
use Civi\Funding\FundingCase\Handler\FundingCaseFormDataGetHandlerInterface;
use Civi\Funding\FundingCase\Handler\FundingCaseFormNewGetHandlerInterface;
use Civi\Funding\FundingCase\Handler\FundingCaseFormNewSubmitHandlerInterface;
use Civi\Funding\FundingCase\Handler\FundingCaseFormNewValidateHandlerInterface;
use Civi\Funding\FundingCase\Handler\FundingCaseFormUpdateGetHandlerInterface;
use Civi\Funding\FundingCase\Handler\FundingCaseFormUpdateSubmitHandlerInterface;
use Civi\Funding\FundingCase\Handler\FundingCaseFormUpdateValidateHandlerInterface;
use Civi\Funding\FundingCase\Handler\FundingCasePossibleActionsGetHandlerInterface;
use Civi\Funding\FundingCase\Handler\FundingCaseUpdateAmountApprovedHandlerInterface;
use Civi\Funding\FundingCase\Handler\TransferContractRecreateHandlerInterface;
use Civi\Funding\FundingCase\StatusDeterminer\FundingCaseStatusDeterminerInterface;
use Civi\Funding\TransferContract\Handler\TransferContractRenderHandlerInterface;
use Psr\Container\ContainerInterface;

final class FundingCaseTypeServiceLocator implements FundingCaseTypeServiceLocatorInterface {

  private ContainerInterface $locator;

  public function __construct(ContainerInterface $locator) {
    $this->locator = $locator;
  }

  public function getApplicationActionApplyHandler(): ApplicationActionApplyHandlerInterface {
    return $this->locator->get(ApplicationActionApplyHandlerInterface::class);
  }

  public function getApplicationAllowedActionsGetHandler(): ApplicationAllowedActionsGetHandlerInterface {
    return $this->locator->get(ApplicationAllowedActionsGetHandlerInterface::class);
  }

  public function getApplicationDeleteHandler(): ApplicationDeleteHandlerInterface {
    return $this->locator->get(ApplicationDeleteHandlerInterface::class);
  }

  public function getApplicationFilesAddIdentifiersHandler() : ApplicationFilesAddIdentifiersHandlerInterface {
    return $this->locator->get(ApplicationFilesAddIdentifiersHandlerInterface::class);
  }

  public function getApplicationFilesPersistHandler(): ApplicationFilesPersistHandlerInterface {
    return $this->locator->get(ApplicationFilesPersistHandlerInterface::class);
  }

  public function getApplicationFormAddCreateHandler(): ?ApplicationFormAddCreateHandlerInterface {
    return $this->getOrNull(ApplicationFormAddCreateHandlerInterface::class);
  }

  public function getApplicationFormAddSubmitHandler(): ?ApplicationFormAddSubmitHandlerInterface {
    return $this->getOrNull(ApplicationFormAddSubmitHandlerInterface::class);
  }

  public function getApplicationFormNewCreateHandler(): ?ApplicationFormNewCreateHandlerInterface {
    return $this->locator->get(ApplicationFormNewCreateHandlerInterface::class);
  }

  public function getApplicationFormNewSubmitHandler(): ?ApplicationFormNewSubmitHandlerInterface {
    return $this->locator->get(ApplicationFormNewSubmitHandlerInterface::class);
  }

  public function getApplicationFormDataGetHandler(): ApplicationFormDataGetHandlerInterface {
    return $this->locator->get(ApplicationFormDataGetHandlerInterface::class);
  }

  public function getApplicationFormCreateHandler(): ApplicationFormCreateHandlerInterface {
    return $this->locator->get(ApplicationFormCreateHandlerInterface::class);
  }

  public function getApplicationFormSubmitHandler(): ApplicationFormSubmitHandlerInterface {
    return $this->locator->get(ApplicationFormSubmitHandlerInterface::class);
  }

  public function getApplicationJsonSchemaGetHandler(): ApplicationJsonSchemaGetHandlerInterface {
    return $this->locator->get(ApplicationJsonSchemaGetHandlerInterface::class);
  }

  public function getApplicationCostItemsPersistHandler(): ApplicationCostItemsPersistHandlerInterface {
    return $this->locator->get(ApplicationCostItemsPersistHandlerInterface::class);
  }

  public function getApplicationResourcesItemsPersistHandler(): ApplicationResourcesItemsPersistHandlerInterface {
    return $this->locator->get(ApplicationResourcesItemsPersistHandlerInterface::class);
  }

  public function getApplicationSnapshotCreateHandler(): ApplicationSnapshotCreateHandlerInterface {
    return $this->locator->get(ApplicationSnapshotCreateHandlerInterface::class);
  }

  public function getApplicationProcessStatusDeterminer(): ApplicationProcessStatusDeterminerInterface {
    return $this->locator->get(ApplicationProcessStatusDeterminerInterface::class);
  }

  public function getFundingCaseApproveHandler(): FundingCaseApproveHandlerInterface {
    return $this->locator->get(FundingCaseApproveHandlerInterface::class);
  }

  public function getFundingCaseFormDataGetHandler(): ?FundingCaseFormDataGetHandlerInterface {
    return $this->getOrNull(FundingCaseFormDataGetHandlerInterface::class);
  }

  public function getFundingCaseFormNewGetHandler(): ?FundingCaseFormNewGetHandlerInterface {
    return $this->getOrNull(FundingCaseFormNewGetHandlerInterface::class);
  }

  public function getFundingCaseFormNewSubmitHandler(): ?FundingCaseFormNewSubmitHandlerInterface {
    return $this->getOrNull(FundingCaseFormNewSubmitHandlerInterface::class);
  }

  public function getFundingCaseFormNewValidateHandler(): ?FundingCaseFormNewValidateHandlerInterface {
    return $this->getOrNull(FundingCaseFormNewValidateHandlerInterface::class);
  }

  public function getFundingCaseFormUpdateGetHandler(): ?FundingCaseFormUpdateGetHandlerInterface {
    return $this->getOrNull(FundingCaseFormUpdateGetHandlerInterface::class);
  }

  public function getFundingCaseFormUpdateSubmitHandler(): ?FundingCaseFormUpdateSubmitHandlerInterface {
    return $this->getOrNull(FundingCaseFormUpdateSubmitHandlerInterface::class);
  }

  public function getFundingCaseFormUpdateValidateHandler(): ?FundingCaseFormUpdateValidateHandlerInterface {
    return $this->getOrNull(FundingCaseFormUpdateValidateHandlerInterface::class);
  }

  public function getFundingCasePossibleActionsGetHandler(): FundingCasePossibleActionsGetHandlerInterface {
    return $this->locator->get(FundingCasePossibleActionsGetHandlerInterface::class);
  }

  public function getFundingCaseStatusDeterminer(): FundingCaseStatusDeterminerInterface {
    return $this->locator->get(FundingCaseStatusDeterminerInterface::class);
  }

  public function getFundingCaseUpdateAmountApprovedHandler(): FundingCaseUpdateAmountApprovedHandlerInterface {
    return $this->locator->get(FundingCaseUpdateAmountApprovedHandlerInterface::class);
  }

  public function getTransferContractRecreateHandler(): TransferContractRecreateHandlerInterface {
    return $this->locator->get(TransferContractRecreateHandlerInterface::class);
  }

  public function getTransferContractRenderHandler(): TransferContractRenderHandlerInterface {
    return $this->locator->get(TransferContractRenderHandlerInterface::class);
  }

  /**
   * @template T of object
   *
   * @phpstan-param class-string<T> $id
   *
   * @phpstan-return T|null
   */
  private function getOrNull(string $id): ?object {
    // @phpstan-ignore-next-line
    return $this->locator->has($id) ? $this->locator->get($id) : NULL;
  }

}
