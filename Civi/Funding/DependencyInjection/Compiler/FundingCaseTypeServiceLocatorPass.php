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

namespace Civi\Funding\DependencyInjection\Compiler;

use Civi\Funding\ApplicationProcess\ActionsDeterminer\ApplicationProcessActionsDeterminerInterface;
use Civi\Funding\ApplicationProcess\ActionStatusInfo\ApplicationProcessActionStatusInfoContainer;
use Civi\Funding\ApplicationProcess\ActionStatusInfo\ApplicationProcessActionStatusInfoInterface;
use Civi\Funding\ApplicationProcess\ApplicationCostItemsFactoryInterface;
use Civi\Funding\ApplicationProcess\ApplicationFormFilesFactoryInterface;
use Civi\Funding\ApplicationProcess\ApplicationResourcesItemsFactoryInterface;
use Civi\Funding\ApplicationProcess\Handler\ApplicationActionApplyHandler;
use Civi\Funding\ApplicationProcess\Handler\ApplicationActionApplyHandlerInterface;
use Civi\Funding\ApplicationProcess\Handler\ApplicationCostItemsAddIdentifiersHandler;
use Civi\Funding\ApplicationProcess\Handler\ApplicationCostItemsAddIdentifiersHandlerInterface;
use Civi\Funding\ApplicationProcess\Handler\ApplicationCostItemsPersistHandler;
use Civi\Funding\ApplicationProcess\Handler\ApplicationCostItemsPersistHandlerInterface;
use Civi\Funding\ApplicationProcess\Handler\ApplicationDeleteHandler;
use Civi\Funding\ApplicationProcess\Handler\ApplicationDeleteHandlerInterface;
use Civi\Funding\ApplicationProcess\Handler\ApplicationFilesAddIdentifiersHandler;
use Civi\Funding\ApplicationProcess\Handler\ApplicationFilesAddIdentifiersHandlerInterface;
use Civi\Funding\ApplicationProcess\Handler\ApplicationFilesPersistHandler;
use Civi\Funding\ApplicationProcess\Handler\ApplicationFilesPersistHandlerInterface;
use Civi\Funding\ApplicationProcess\Handler\ApplicationFormAddCreateHandler;
use Civi\Funding\ApplicationProcess\Handler\ApplicationFormAddCreateHandlerInterface;
use Civi\Funding\ApplicationProcess\Handler\ApplicationFormAddSubmitHandler;
use Civi\Funding\ApplicationProcess\Handler\ApplicationFormAddSubmitHandlerInterface;
use Civi\Funding\ApplicationProcess\Handler\ApplicationFormAddValidateHandler;
use Civi\Funding\ApplicationProcess\Handler\ApplicationFormAddValidateHandlerInterface;
use Civi\Funding\ApplicationProcess\Handler\ApplicationFormCommentPersistHandler;
use Civi\Funding\ApplicationProcess\Handler\ApplicationFormCommentPersistHandlerInterface;
use Civi\Funding\ApplicationProcess\Handler\ApplicationFormCreateHandler;
use Civi\Funding\ApplicationProcess\Handler\ApplicationFormCreateHandlerInterface;
use Civi\Funding\ApplicationProcess\Handler\ApplicationFormDataGetHandler;
use Civi\Funding\ApplicationProcess\Handler\ApplicationFormDataGetHandlerInterface;
use Civi\Funding\ApplicationProcess\Handler\ApplicationFormNewCreateHandler;
use Civi\Funding\ApplicationProcess\Handler\ApplicationFormNewCreateHandlerInterface;
use Civi\Funding\ApplicationProcess\Handler\ApplicationFormNewSubmitHandler;
use Civi\Funding\ApplicationProcess\Handler\ApplicationFormNewSubmitHandlerInterface;
use Civi\Funding\ApplicationProcess\Handler\ApplicationFormNewValidateHandler;
use Civi\Funding\ApplicationProcess\Handler\ApplicationFormNewValidateHandlerInterface;
use Civi\Funding\ApplicationProcess\Handler\ApplicationFormSubmitHandler;
use Civi\Funding\ApplicationProcess\Handler\ApplicationFormSubmitHandlerInterface;
use Civi\Funding\ApplicationProcess\Handler\ApplicationFormValidateHandler;
use Civi\Funding\ApplicationProcess\Handler\ApplicationFormValidateHandlerInterface;
use Civi\Funding\ApplicationProcess\Handler\ApplicationJsonSchemaGetHandler;
use Civi\Funding\ApplicationProcess\Handler\ApplicationJsonSchemaGetHandlerInterface;
use Civi\Funding\ApplicationProcess\Handler\ApplicationResourcesItemsAddIdentifiersHandler;
use Civi\Funding\ApplicationProcess\Handler\ApplicationResourcesItemsAddIdentifiersHandlerInterface;
use Civi\Funding\ApplicationProcess\Handler\ApplicationResourcesItemsPersistHandler;
use Civi\Funding\ApplicationProcess\Handler\ApplicationResourcesItemsPersistHandlerInterface;
use Civi\Funding\ApplicationProcess\Handler\ApplicationSnapshotCreateHandler;
use Civi\Funding\ApplicationProcess\Handler\ApplicationSnapshotCreateHandlerInterface;
use Civi\Funding\ApplicationProcess\Handler\Decorator\ApplicationFormAddSubmitEventDecorator;
use Civi\Funding\ApplicationProcess\Handler\Decorator\ApplicationFormNewSubmitEventDecorator;
use Civi\Funding\ApplicationProcess\Handler\Decorator\ApplicationFormSubmitEventDecorator;
use Civi\Funding\ApplicationProcess\StatusDeterminer\ApplicationProcessStatusDeterminerInterface;
use Civi\Funding\Form\ApplicationFormDataFactoryInterface;
use Civi\Funding\Form\ApplicationJsonSchemaFactoryInterface;
use Civi\Funding\Form\ApplicationUiSchemaFactoryInterface;
use Civi\Funding\Form\ApplicationValidatorInterface;
use Civi\Funding\Form\FundingCase\FundingCaseFormDataFactoryInterface;
use Civi\Funding\Form\FundingCase\FundingCaseJsonSchemaFactoryInterface;
use Civi\Funding\Form\FundingCase\FundingCaseUiSchemaFactoryInterface;
use Civi\Funding\Form\FundingCase\FundingCaseValidatorInterface;
use Civi\Funding\Form\NonSummaryApplicationJsonSchemaFactoryInterface;
use Civi\Funding\Form\NonSummaryApplicationValidatorInterface;
use Civi\Funding\Form\SummaryApplicationJsonSchemaFactoryInterface;
use Civi\Funding\FundingCase\Actions\FundingCaseActionsDeterminerInterface;
use Civi\Funding\FundingCase\Handler\Helper\ApplicationAllowedActionApplier;
use Civi\Funding\FundingCase\NonSummaryFundingCaseStatusDeterminer;
use Civi\Funding\FundingCase\FundingCaseStatusDeterminerInterface;
use Civi\Funding\FundingCase\Handler\Decorator\FundingCaseApproveEventDecorator;
use Civi\Funding\FundingCase\Handler\FundingCaseApproveHandler;
use Civi\Funding\FundingCase\Handler\FundingCaseApproveHandlerInterface;
use Civi\Funding\FundingCase\Handler\FundingCaseFormDataGetHandler;
use Civi\Funding\FundingCase\Handler\FundingCaseFormDataGetHandlerInterface;
use Civi\Funding\FundingCase\Handler\FundingCaseFormNewGetHandler;
use Civi\Funding\FundingCase\Handler\FundingCaseFormNewGetHandlerInterface;
use Civi\Funding\FundingCase\Handler\FundingCaseFormNewSubmitHandler;
use Civi\Funding\FundingCase\Handler\FundingCaseFormNewSubmitHandlerInterface;
use Civi\Funding\FundingCase\Handler\FundingCaseFormNewValidateHandler;
use Civi\Funding\FundingCase\Handler\FundingCaseFormNewValidateHandlerInterface;
use Civi\Funding\FundingCase\Handler\FundingCaseFormUpdateGetHandler;
use Civi\Funding\FundingCase\Handler\FundingCaseFormUpdateGetHandlerInterface;
use Civi\Funding\FundingCase\Handler\FundingCaseFormUpdateSubmitHandler;
use Civi\Funding\FundingCase\Handler\FundingCaseFormUpdateSubmitHandlerInterface;
use Civi\Funding\FundingCase\Handler\FundingCaseFormUpdateValidateHandler;
use Civi\Funding\FundingCase\Handler\FundingCaseFormUpdateValidateHandlerInterface;
use Civi\Funding\FundingCase\Handler\FundingCasePossibleActionsGetHandler;
use Civi\Funding\FundingCase\Handler\FundingCasePossibleActionsGetHandlerInterface;
use Civi\Funding\FundingCase\Handler\TransferContractRecreateHandler;
use Civi\Funding\FundingCase\Handler\TransferContractRecreateHandlerInterface;
use Civi\Funding\FundingCase\SummaryFundingCaseStatusDeterminer;
use Civi\Funding\FundingCaseTypeServiceLocator;
use Civi\Funding\FundingCaseTypeServiceLocatorContainer;
use Civi\Funding\FundingCaseTypeServiceLocatorInterface;
use Civi\Funding\TransferContract\Handler\TransferContractRenderHandler;
use Civi\Funding\TransferContract\Handler\TransferContractRenderHandlerInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @codeCoverageIgnore
 */
final class FundingCaseTypeServiceLocatorPass implements CompilerPassInterface {

  /**
   * @phpstan-var array<string>
   */
  private array $fundingCaseTypes = [];

  /**
   * @inheritDoc
   *
   * @throws \Symfony\Component\DependencyInjection\Exception\RuntimeException
   */
  // phpcs:disable Generic.Metrics.CyclomaticComplexity.TooHigh, Generic.Metrics.CyclomaticComplexity.MaxExceeded
  public function process(ContainerBuilder $container): void {
  // phpcs:enable
    $applicationActionStatusInfoServices =
      $this->getTaggedServices($container, ApplicationProcessActionStatusInfoInterface::SERVICE_TAG);

    $applicationFormDataFactoryServices =
      $this->getTaggedServices($container, ApplicationFormDataFactoryInterface::SERVICE_TAG);
    $applicationJsonSchemaFactoryServices =
      $this->getTaggedServices($container, ApplicationJsonSchemaFactoryInterface::SERVICE_TAG);
    $applicationUiSchemaFactoryServices =
      $this->getTaggedServices($container, ApplicationUiSchemaFactoryInterface::SERVICE_TAG);
    $applicationValidator = $this->getTaggedServices($container, ApplicationValidatorInterface::SERVICE_TAG);
    $applicationActionsDeterminerServices =
      $this->getTaggedServices($container, ApplicationProcessActionsDeterminerInterface::SERVICE_TAG);
    $applicationStatusDeterminerServices =
      $this->getTaggedServices($container, ApplicationProcessStatusDeterminerInterface::SERVICE_TAG);
    $applicationCostItemsFactoryServices =
      $this->getTaggedServices($container, ApplicationCostItemsFactoryInterface::SERVICE_TAG);
    $applicationResourcesItemsFactoryServices =
      $this->getTaggedServices($container, ApplicationResourcesItemsFactoryInterface::SERVICE_TAG);
    $applicationFormFilesFactoryServices =
      $this->getTaggedServices($container, ApplicationFormFilesFactoryInterface::SERVICE_TAG);

    $applicationDeleteHandlerServices =
      $this->getTaggedServices($container, ApplicationDeleteHandlerInterface::SERVICE_TAG);

    $applicationFormNewCreateHandlerServices =
      $this->getTaggedServices($container, ApplicationFormNewCreateHandlerInterface::SERVICE_TAG);
    $applicationFormNewValidateHandlerServices =
      $this->getTaggedServices($container, ApplicationFormValidateHandlerInterface::SERVICE_TAG);
    $applicationFormNewSubmitHandlerServices =
      $this->getTaggedServices($container, ApplicationFormNewSubmitHandlerInterface::SERVICE_TAG);

    $applicationFormAddCreateHandlerServices =
      $this->getTaggedServices($container, ApplicationFormAddCreateHandlerInterface::SERVICE_TAG);
    $applicationFormAddValidateHandlerServices =
      $this->getTaggedServices($container, ApplicationFormAddValidateHandlerInterface::SERVICE_TAG);
    $applicationFormAddSubmitHandlerServices =
      $this->getTaggedServices($container, ApplicationFormAddSubmitHandlerInterface::SERVICE_TAG);

    $applicationFormCreateHandlerServices =
      $this->getTaggedServices($container, ApplicationFormCreateHandlerInterface::SERVICE_TAG);
    $applicationFormDataGetHandlerServices =
      $this->getTaggedServices($container, ApplicationFormDataGetHandlerInterface::SERVICE_TAG);
    $applicationFormValidateHandlerServices =
      $this->getTaggedServices($container, ApplicationFormValidateHandlerInterface::SERVICE_TAG);
    $applicationFormSubmitHandlerServices =
      $this->getTaggedServices($container, ApplicationFormSubmitHandlerInterface::SERVICE_TAG);

    $applicationActionApplyHandlerServices =
      $this->getTaggedServices($container, ApplicationActionApplyHandlerInterface::SERVICE_TAG);

    $applicationFormCommentPersistHandlerServices =
      $this->getTaggedServices($container, ApplicationFormCommentPersistHandlerInterface::SERVICE_TAG);
    $applicationFormJsonSchemaGetHandlerServices =
      $this->getTaggedServices($container, ApplicationJsonSchemaGetHandlerInterface::SERVICE_TAG);

    $applicationCostItemsAddIdentifiersHandlerServices =
      $this->getTaggedServices($container, ApplicationCostItemsAddIdentifiersHandlerInterface::SERVICE_TAG);
    $applicationCostItemsPersistHandlerServices =
      $this->getTaggedServices($container, ApplicationCostItemsPersistHandlerInterface::SERVICE_TAG);

    $applicationResourcesItemsAddIdentifiersHandlerServices =
      $this->getTaggedServices($container, ApplicationResourcesItemsAddIdentifiersHandlerInterface::SERVICE_TAG);
    $applicationResourcesItemsPersistHandlerServices =
      $this->getTaggedServices($container, ApplicationResourcesItemsPersistHandlerInterface::SERVICE_TAG);

    $applicationFilesAddIdentifiersHandlerServices =
      $this->getTaggedServices($container, ApplicationFilesAddIdentifiersHandlerInterface::SERVICE_TAG);
    $applicationFilesPersistHandlerServices =
      $this->getTaggedServices($container, ApplicationFilesPersistHandlerInterface::SERVICE_TAG);

    $applicationSnapshotCreateHandlerServices =
      $this->getTaggedServices($container, ApplicationSnapshotCreateHandlerInterface::SERVICE_TAG);

    $fundingCaseActionsDeterminerServices =
      $this->getTaggedServices($container, FundingCaseActionsDeterminerInterface::SERVICE_TAG);
    $fundingCaseStatusDeterminerServices =
      $this->getTaggedServices($container, FundingCaseStatusDeterminerInterface::SERVICE_TAG);

    $fundingCaseFormDataFactoryServices =
      $this->getTaggedServices($container, FundingCaseFormDataFactoryInterface::SERVICE_TAG);
    $fundingCaseJsonSchemaFactoryServices =
      $this->getTaggedServices($container, FundingCaseJsonSchemaFactoryInterface::SERVICE_TAG);
    $fundingCaseUiSchemaFactoryServices =
      $this->getTaggedServices($container, FundingCaseUiSchemaFactoryInterface::SERVICE_TAG);
    $fundingCaseValidatorServices = $this->getTaggedServices($container, FundingCaseValidatorInterface::SERVICE_TAG);

    $fundingCaseFormNewGetHandlerServices =
      $this->getTaggedServices($container, FundingCaseFormNewGetHandlerInterface::SERVICE_TAG);
    $fundingCaseFormNewSubmitHandlerServices =
      $this->getTaggedServices($container, FundingCaseFormNewSubmitHandlerInterface::SERVICE_TAG);
    $fundingCaseFormNewValidateHandlerServices =
      $this->getTaggedServices($container, FundingCaseFormNewValidateHandlerInterface::SERVICE_TAG);

    $fundingCaseFormUpdateGetHandlerServices =
      $this->getTaggedServices($container, FundingCaseFormUpdateGetHandlerInterface::SERVICE_TAG);
    $fundingCaseFormDataGetHandlerServices =
      $this->getTaggedServices($container, FundingCaseFormDataGetHandlerInterface::SERVICE_TAG);
    $fundingCaseFormUpdateSubmitHandlerServices =
      $this->getTaggedServices($container, FundingCaseFormUpdateSubmitHandlerInterface::SERVICE_TAG);
    $fundingCaseFormUpdateValidateHandlerServices =
      $this->getTaggedServices($container, FundingCaseFormUpdateValidateHandlerInterface::SERVICE_TAG);

    $fundingCaseApproveHandlerServices =
      $this->getTaggedServices($container, FundingCaseApproveHandlerInterface::SERVICE_TAG);
    $fundingCasePossibleActionsGetHandlerServices =
      $this->getTaggedServices($container, FundingCasePossibleActionsGetHandlerInterface::SERVICE_TAG);

    $transferContractRecreateHandlerServices =
      $this->getTaggedServices($container, TransferContractRecreateHandlerInterface::SERVICE_TAG);
    $transferContractRenderHandlerServices =
      $this->getTaggedServices($container, TransferContractRenderHandlerInterface::SERVICE_TAG);

    $serviceLocatorServices =
      $this->getTaggedServices($container, FundingCaseTypeServiceLocatorInterface::SERVICE_TAG);

    foreach ($this->fundingCaseTypes as $fundingCaseType) {
      if (!isset($applicationActionStatusInfoServices[$fundingCaseType])) {
        throw new RuntimeException(
          sprintf('Application action status info for funding case type "%s" missing', $fundingCaseType)
        );
      }

      if (isset($serviceLocatorServices[$fundingCaseType])) {
        continue;
      }

      $applicationDeleteHandlerServices[$fundingCaseType] ??= $this->createService(
        $container,
        $fundingCaseType,
        ApplicationDeleteHandler::class,
        [
          '$actionsDeterminer' => $applicationActionsDeterminerServices[$fundingCaseType],
        ]
      );

      if ($this->isServiceReferenceInstanceOf(
        $container,
        $applicationJsonSchemaFactoryServices[$fundingCaseType] ?? NULL,
        NonSummaryApplicationJsonSchemaFactoryInterface::class
      ) || $this->isServiceReferenceInstanceOf(
        $container,
          $applicationValidator[$fundingCaseType] ?? NULL,
        NonSummaryApplicationValidatorInterface::class
      )) {
        $fundingCaseStatusDeterminerServices[$fundingCaseType] ??= $this->createService(
          $container,
          $fundingCaseType,
          NonSummaryFundingCaseStatusDeterminer::class,
          [
            '$info' => $applicationActionStatusInfoServices[$fundingCaseType],
          ]
        );

        $applicationFormNewCreateHandlerServices[$fundingCaseType] ??= $this->createService(
          $container, $fundingCaseType, ApplicationFormNewCreateHandler::class, [
            '$jsonSchemaFactory' => $applicationJsonSchemaFactoryServices[$fundingCaseType],
            '$uiSchemaFactory' => $applicationUiSchemaFactoryServices[$fundingCaseType],
          ]
        );

        $applicationFormNewValidateHandlerServices[$fundingCaseType] ??= $this->createService(
          $container,
          $fundingCaseType,
          ApplicationFormNewValidateHandler::class,
          ['$validator' => $applicationValidator[$fundingCaseType]]
        );

        $applicationFormNewSubmitHandlerServices[$fundingCaseType] ??= $this->createService(
          $container,
          $fundingCaseType,
          ApplicationFormNewSubmitHandler::class,
          [
            '$statusDeterminer' => $applicationStatusDeterminerServices[$fundingCaseType],
            '$validator' => $applicationValidator[$fundingCaseType],
          ],
          [ApplicationFormNewSubmitEventDecorator::class => []],
        );
      }

      if ($this->isServiceReferenceInstanceOf(
          $container,
          $applicationJsonSchemaFactoryServices[$fundingCaseType] ?? NULL,
          SummaryApplicationJsonSchemaFactoryInterface::class
      ) || isset($fundingCaseJsonSchemaFactoryServices[$fundingCaseType])
      ) {
        $fundingCaseStatusDeterminerServices[$fundingCaseType] ??= $this->createService(
          $container,
          $fundingCaseType,
          SummaryFundingCaseStatusDeterminer::class,
          [],
        );

        $applicationFormAddCreateHandlerServices[$fundingCaseType] ??= $this->createService(
          $container,
          $fundingCaseType,
          ApplicationFormAddCreateHandler::class,
          [
            '$jsonSchemaFactory' => $applicationJsonSchemaFactoryServices[$fundingCaseType],
            '$uiSchemaFactory' => $applicationUiSchemaFactoryServices[$fundingCaseType],
          ],
        );

        $applicationFormAddValidateHandlerServices[$fundingCaseType] ??= $this->createService(
          $container,
          $fundingCaseType,
          ApplicationFormAddValidateHandler::class,
          ['$validator' => $applicationValidator[$fundingCaseType]],
        );

        $applicationFormAddSubmitHandlerServices[$fundingCaseType] ??= $this->createService(
          $container,
          $fundingCaseType,
          ApplicationFormAddSubmitHandler::class,
          [
            '$statusDeterminer' => $applicationStatusDeterminerServices[$fundingCaseType],
          ],
          [ApplicationFormAddSubmitEventDecorator::class => []],
        );

        $fundingCaseFormNewGetHandlerServices[$fundingCaseType] ??= $this->createService(
          $container,
          $fundingCaseType,
          FundingCaseFormNewGetHandler::class,
          [
            '$jsonSchemaFactory' => $fundingCaseJsonSchemaFactoryServices[$fundingCaseType],
            '$uiSchemaFactory' => $fundingCaseUiSchemaFactoryServices[$fundingCaseType],
          ],
        );

        $fundingCaseFormNewSubmitHandlerServices[$fundingCaseType] ??= $this->createService(
          $container,
          $fundingCaseType,
          FundingCaseFormNewSubmitHandler::class,
          [],
        );

        $fundingCaseFormNewValidateHandlerServices[$fundingCaseType] ??= $this->createService(
          $container,
          $fundingCaseType,
          FundingCaseFormNewValidateHandler::class,
          [
            '$validator' => $fundingCaseValidatorServices[$fundingCaseType],
          ],
        );

        $fundingCaseFormUpdateGetHandlerServices[$fundingCaseType] ??= $this->createService(
          $container,
          $fundingCaseType,
          FundingCaseFormUpdateGetHandler::class,
          [
            '$jsonSchemaFactory' => $fundingCaseJsonSchemaFactoryServices[$fundingCaseType],
            '$uiSchemaFactory' => $fundingCaseUiSchemaFactoryServices[$fundingCaseType],
          ],
        );

        $fundingCaseFormDataGetHandlerServices[$fundingCaseType] ??= $this->createService(
          $container,
          $fundingCaseType,
          FundingCaseFormDataGetHandler::class,
          ['$formDataFactory' => $fundingCaseFormDataFactoryServices[$fundingCaseType]],
        );

        $fundingCaseFormUpdateSubmitHandlerServices[$fundingCaseType] ??= $this->createService(
          $container,
          $fundingCaseType,
          FundingCaseFormUpdateSubmitHandler::class,
          [
            '$applicationAllowedActionApplier' => $this->createService(
              $container,
              $fundingCaseType,
              ApplicationAllowedActionApplier::class,
              ['$actionsDeterminer' => $applicationActionsDeterminerServices[$fundingCaseType]],
            ),
            '$statusDeterminer' => $fundingCaseStatusDeterminerServices[$fundingCaseType],
          ],
        );

        $fundingCaseFormUpdateValidateHandlerServices[$fundingCaseType] ??= $this->createService(
          $container,
          $fundingCaseType,
          FundingCaseFormUpdateValidateHandler::class,
          [
            '$validator' => $fundingCaseValidatorServices[$fundingCaseType],
          ],
        );
      }

      $applicationFormJsonSchemaGetHandlerServices[$fundingCaseType] ??= $this->createService(
        $container,
        $fundingCaseType,
        ApplicationJsonSchemaGetHandler::class,
        ['$jsonSchemaFactory' => $applicationJsonSchemaFactoryServices[$fundingCaseType]]
      );

      $applicationFormValidateHandlerServices[$fundingCaseType] ??= $this->createService(
        $container,
        $fundingCaseType,
        ApplicationFormValidateHandler::class,
        ['$validator' => $applicationValidator[$fundingCaseType]]
      );

      $applicationFormDataGetHandlerServices[$fundingCaseType] ??= $this->createService(
        $container,
        $fundingCaseType,
        ApplicationFormDataGetHandler::class,
        [
          '$formDataFactory' => $applicationFormDataFactoryServices[$fundingCaseType],
          '$validateHandler' => $applicationFormValidateHandlerServices[$fundingCaseType],
        ]
      );

      $applicationFormCreateHandlerServices[$fundingCaseType] ??= $this->createService(
        $container,
        $fundingCaseType,
        ApplicationFormCreateHandler::class,
        [
          '$jsonSchemaGetHandler' => $applicationFormJsonSchemaGetHandlerServices[$fundingCaseType],
          '$uiSchemaFactory' => $applicationUiSchemaFactoryServices[$fundingCaseType],
          '$dataGetHandler' => $applicationFormDataGetHandlerServices[$fundingCaseType],
        ]
      );

      $applicationFormCommentPersistHandlerServices[$fundingCaseType] ??= $this->createService(
        $container,
        $fundingCaseType,
        ApplicationFormCommentPersistHandler::class,
        []
      );

      $applicationFormSubmitHandlerServices[$fundingCaseType] ??= $this->createService(
        $container,
        $fundingCaseType,
        ApplicationFormSubmitHandler::class,
        [],
        [ApplicationFormSubmitEventDecorator::class => []],
      );

      $applicationActionApplyHandlerServices[$fundingCaseType] ??= $this->createService(
        $container,
        $fundingCaseType,
        ApplicationActionApplyHandler::class,
        [
          '$commentPersistHandler' => $applicationFormCommentPersistHandlerServices[$fundingCaseType],
          '$info' => $applicationActionStatusInfoServices[$fundingCaseType],
          '$statusDeterminer' => $applicationStatusDeterminerServices[$fundingCaseType],
        ]
      );

      $applicationCostItemsAddIdentifiersHandlerServices[$fundingCaseType] ??= $this->createService(
        $container,
        $fundingCaseType,
        ApplicationCostItemsAddIdentifiersHandler::class,
        ['$costItemsFactory' => $applicationCostItemsFactoryServices[$fundingCaseType]]
      );

      $applicationCostItemsPersistHandlerServices[$fundingCaseType] ??= $this->createService(
        $container,
        $fundingCaseType,
        ApplicationCostItemsPersistHandler::class,
        ['$costItemsFactory' => $applicationCostItemsFactoryServices[$fundingCaseType]]
      );

      $applicationResourcesItemsAddIdentifiersHandlerServices[$fundingCaseType] ??= $this->createService(
        $container,
        $fundingCaseType,
        ApplicationResourcesItemsAddIdentifiersHandler::class,
        ['$resourcesItemsFactory' => $applicationResourcesItemsFactoryServices[$fundingCaseType]]
      );

      $applicationResourcesItemsPersistHandlerServices[$fundingCaseType] ??= $this->createService(
        $container,
        $fundingCaseType,
        ApplicationResourcesItemsPersistHandler::class,
        ['$resourcesItemsFactory' => $applicationResourcesItemsFactoryServices[$fundingCaseType]]
      );

      $applicationFilesAddIdentifiersHandlerServices[$fundingCaseType] ??= $this->createService(
        $container,
        $fundingCaseType,
        ApplicationFilesAddIdentifiersHandler::class,
        ['$formFilesFactory' => $applicationFormFilesFactoryServices[$fundingCaseType]]
      );

      $applicationFilesPersistHandlerServices[$fundingCaseType] ??= $this->createService(
        $container,
        $fundingCaseType,
        ApplicationFilesPersistHandler::class,
        ['$formFilesFactory' => $applicationFormFilesFactoryServices[$fundingCaseType]]
      );

      $applicationSnapshotCreateHandlerServices[$fundingCaseType] ??= $this->createService(
        $container,
        $fundingCaseType,
        ApplicationSnapshotCreateHandler::class,
        []
      );

      $fundingCaseApproveHandlerServices[$fundingCaseType] ??= $this->createService(
        $container,
        $fundingCaseType,
        FundingCaseApproveHandler::class,
        [
          '$actionsDeterminer' => $fundingCaseActionsDeterminerServices[$fundingCaseType],
          '$statusDeterminer' => $fundingCaseStatusDeterminerServices[$fundingCaseType],
        ],
        [FundingCaseApproveEventDecorator::class => []],
      );

      $fundingCasePossibleActionsGetHandlerServices[$fundingCaseType] ??= $this->createService(
        $container,
        $fundingCaseType,
        FundingCasePossibleActionsGetHandler::class,
        ['$actionsDeterminer' => $fundingCaseActionsDeterminerServices[$fundingCaseType]],
      );

      $transferContractRecreateHandlerServices[$fundingCaseType] ??= $this->createService(
        $container,
        $fundingCaseType,
        TransferContractRecreateHandler::class,
        ['$actionsDeterminer' => $fundingCaseActionsDeterminerServices[$fundingCaseType]]
      );

      $transferContractRenderHandlerServices[$fundingCaseType] ??= $this->createService(
        $container,
        $fundingCaseType,
        TransferContractRenderHandler::class,
        [],
      );

      $services = [
        ApplicationActionApplyHandlerInterface::class => $applicationActionApplyHandlerServices[$fundingCaseType],
        ApplicationDeleteHandlerInterface::class => $applicationDeleteHandlerServices[$fundingCaseType],
        ApplicationFilesAddIdentifiersHandlerInterface::class
        => $applicationFilesAddIdentifiersHandlerServices[$fundingCaseType],
        ApplicationFilesPersistHandlerInterface::class => $applicationFilesPersistHandlerServices[$fundingCaseType],
        ApplicationFormCreateHandlerInterface::class
        => $applicationFormCreateHandlerServices[$fundingCaseType],
        ApplicationFormDataGetHandlerInterface::class => $applicationFormDataGetHandlerServices[$fundingCaseType],
        ApplicationFormValidateHandlerInterface::class
        => $applicationFormValidateHandlerServices[$fundingCaseType],
        ApplicationFormSubmitHandlerInterface::class => $applicationFormSubmitHandlerServices[$fundingCaseType],
        ApplicationJsonSchemaGetHandlerInterface::class
        => $applicationFormJsonSchemaGetHandlerServices[$fundingCaseType],
        ApplicationCostItemsAddIdentifiersHandlerInterface::class
        => $applicationCostItemsAddIdentifiersHandlerServices[$fundingCaseType],
        ApplicationCostItemsPersistHandlerInterface::class
        => $applicationCostItemsPersistHandlerServices[$fundingCaseType],
        ApplicationResourcesItemsAddIdentifiersHandlerInterface::class
        => $applicationResourcesItemsAddIdentifiersHandlerServices[$fundingCaseType],
        ApplicationResourcesItemsPersistHandlerInterface::class
        => $applicationResourcesItemsPersistHandlerServices[$fundingCaseType],
        ApplicationSnapshotCreateHandlerInterface::class => $applicationSnapshotCreateHandlerServices[$fundingCaseType],
        FundingCaseApproveHandlerInterface::class => $fundingCaseApproveHandlerServices[$fundingCaseType],
        FundingCaseStatusDeterminerInterface::class => $fundingCaseStatusDeterminerServices[$fundingCaseType],
        FundingCasePossibleActionsGetHandlerInterface::class
        => $fundingCasePossibleActionsGetHandlerServices[$fundingCaseType],
        TransferContractRecreateHandlerInterface::class => $transferContractRecreateHandlerServices[$fundingCaseType],
        TransferContractRenderHandlerInterface::class => $transferContractRenderHandlerServices[$fundingCaseType],
      ];

      if (isset($applicationFormNewCreateHandlerServices[$fundingCaseType])) {
        $services[ApplicationFormNewCreateHandlerInterface::class] =
          $applicationFormNewCreateHandlerServices[$fundingCaseType];
        $services[ApplicationFormNewValidateHandlerInterface::class] =
          $applicationFormNewValidateHandlerServices[$fundingCaseType];
        $services[ApplicationFormNewSubmitHandlerInterface::class] =
          $applicationFormNewSubmitHandlerServices[$fundingCaseType];
      }

      if (isset($applicationFormAddCreateHandlerServices[$fundingCaseType])) {
        $services[ApplicationFormAddCreateHandlerInterface::class] =
          $applicationFormAddCreateHandlerServices[$fundingCaseType];
        $services[ApplicationFormAddValidateHandlerInterface::class] =
          $applicationFormAddValidateHandlerServices[$fundingCaseType];
        $services[ApplicationFormAddSubmitHandlerInterface::class] =
          $applicationFormAddSubmitHandlerServices[$fundingCaseType];

        $services[FundingCaseFormNewGetHandlerInterface::class] =
          $fundingCaseFormNewGetHandlerServices[$fundingCaseType];
        $services[FundingCaseFormNewSubmitHandlerInterface::class] =
          $fundingCaseFormNewSubmitHandlerServices[$fundingCaseType];
        $services[FundingCaseFormNewValidateHandlerInterface::class] =
          $fundingCaseFormNewValidateHandlerServices[$fundingCaseType];
      }

      if (isset($fundingCaseFormUpdateGetHandlerServices[$fundingCaseType])) {
        $services[FundingCaseFormUpdateGetHandlerInterface::class] =
          $fundingCaseFormUpdateGetHandlerServices[$fundingCaseType];
        $services[FundingCaseFormDataGetHandlerInterface::class] =
          $fundingCaseFormDataGetHandlerServices[$fundingCaseType];
        $services[FundingCaseFormUpdateValidateHandlerInterface::class] =
          $fundingCaseFormUpdateValidateHandlerServices[$fundingCaseType];
        $services[FundingCaseFormUpdateSubmitHandlerInterface::class] =
          $fundingCaseFormUpdateSubmitHandlerServices[$fundingCaseType];
      }

      $serviceLocatorServices[$fundingCaseType] = $this->createService(
        $container,
        $fundingCaseType,
        FundingCaseTypeServiceLocator::class,
        [ServiceLocatorTagPass::register($container, $services)]
      );
    }

    foreach (array_keys($applicationStatusDeterminerServices) as $fundingCaseType) {
      if (!isset($serviceLocatorServices[$fundingCaseType])) {
        throw new RuntimeException(sprintf('No form factory for funding case type "%s" defined', $fundingCaseType));
      }
    }

    $container->register(
      ApplicationProcessActionStatusInfoContainer::class,
      ApplicationProcessActionStatusInfoContainer::class
    )->addArgument(ServiceLocatorTagPass::register($container, $applicationActionStatusInfoServices));

    $container->register(FundingCaseTypeServiceLocatorContainer::class, FundingCaseTypeServiceLocatorContainer::class)
      ->addArgument(ServiceLocatorTagPass::register($container, $serviceLocatorServices));
  }

  /**
   * @phpstan-param array<string|int, Reference> $arguments
   * @phpstan-param array<string, array<string|int, Reference>> $decorators
   *   Class names mapped to arguments. The handler to decorate has to be the
   *   first argument in the decorator class constructor.
   */
  private function createService(
    ContainerBuilder $container,
    string $fundingCaseType,
    string $class,
    array $arguments,
    array $decorators = []
  ): Reference {
    $serviceId = $class;
    if ([] !== $arguments) {
      $serviceId .= ':' . $fundingCaseType;
    }
    $container->autowire($serviceId, $class)->setArguments($arguments);

    foreach ($decorators as $decoratorClass => $decoratorArguments) {
      $decoratorServiceId = $decoratorClass . ':' . $fundingCaseType;
      array_unshift($decoratorArguments, new Reference($serviceId));
      $container->autowire($decoratorServiceId, $decoratorClass)->setArguments($decoratorArguments);
      $serviceId = $decoratorServiceId;
    }

    return new Reference($serviceId);
  }

  /**
   * @phpstan-return array<string, Reference>
   *
   * @throws \Symfony\Component\DependencyInjection\Exception\RuntimeException
   */
  private function getTaggedServices(ContainerBuilder $container, string $tagName): array {
    $services = [];
    foreach ($container->findTaggedServiceIds($tagName) as $id => $tags) {
      foreach ($tags as $attributes) {
        foreach ($this->getFundingCaseTypes($container, $id, $attributes) as $fundingCaseType) {
          if (isset($services[$fundingCaseType])) {
            throw new RuntimeException(
              sprintf('Duplicate service with tag "%s" and funding case type "%s"', $tagName, $fundingCaseType)
            );
          }
          $services[$fundingCaseType] = new Reference($id);
          if (!in_array($fundingCaseType, $this->fundingCaseTypes, TRUE)) {
            $this->fundingCaseTypes[] = $fundingCaseType;
          }
        }
      }
    }

    return $services;
  }

  /**
   * @phpstan-param array{funding_case_type?: string, funding_case_types?: array<string>} $attributes
   *
   * @phpstan-return array<string>
   *
   * @throws \Symfony\Component\DependencyInjection\Exception\RuntimeException
   */
  private function getFundingCaseTypes(ContainerBuilder $container, string $id, array $attributes): array {
    if (array_key_exists('funding_case_types', $attributes)) {
      return $attributes['funding_case_types'];
    }

    if (array_key_exists('funding_case_type', $attributes)) {
      return [$attributes['funding_case_type']];
    }

    $class = $this->getServiceClass($container, $id);
    if (method_exists($class, 'getSupportedFundingCaseTypes')) {
      /** @phpstan-var array<string> $fundingCaseTypes */
      $fundingCaseTypes = $class::getSupportedFundingCaseTypes();

      return $fundingCaseTypes;
    }

    if (!method_exists($class, 'getSupportedFundingCaseType')) {
      throw new RuntimeException(sprintf('No funding case type specified for service "%s"', $id));
    }

    /** @var string $fundingCaseType */
    $fundingCaseType = $class::getSupportedFundingCaseType();

    return [$fundingCaseType];
  }

  /**
   * @phpstan-return class-string
   */
  private function getServiceClass(ContainerBuilder $container, string $id): string {
    $definition = $container->getDefinition($id);

    /** @phpstan-var class-string $class */
    $class = $definition->getClass() ?? $id;

    return $class;
  }

  private function isServiceReferenceInstanceOf(
    ContainerBuilder $container,
    ?Reference $reference,
    string $class
  ): bool {
    if (NULL === $reference) {
      return FALSE;
    }

    return is_a($this->getServiceClass($container, (string) $reference), $class, TRUE);
  }

}
