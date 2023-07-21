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

// This file is prefix by "z_" so it is loaded as last one.

// phpcs:disable Drupal.Commenting.DocComment.ContentAfterOpen
/** @var \Symfony\Component\DependencyInjection\ContainerBuilder $container */

use Civi\Funding\ApplicationProcess\ActionsDeterminer\ReworkPossibleApplicationProcessActionsDeterminer;
use Civi\Funding\ApplicationProcess\ActionStatusInfo\ReworkPossibleApplicationProcessActionStatusInfo;
use Civi\Funding\ApplicationProcess\StatusDeterminer\ReworkPossibleApplicationProcessStatusDeterminer;
use Civi\Funding\Form\ApplicationSubmitActionsFactory;
use Civi\Funding\Form\SonstigeAktivitaet\AVK1FormDataFactory;
use Civi\Funding\Form\SonstigeAktivitaet\AVK1JsonSchemaFactory;
use Civi\Funding\Form\SonstigeAktivitaet\AVK1UiSchemaFactory;
use Civi\Funding\FundingCase\DefaultFundingCaseActionsDeterminer;
use Civi\Funding\SonstigeAktivitaet\AVK1ApplicationCostItemsFactory;
use Civi\Funding\SonstigeAktivitaet\AVK1ApplicationFormFilesFactory;
use Civi\Funding\SonstigeAktivitaet\AVK1ApplicationResourcesItemsFactory;
use Civi\Funding\SonstigeAktivitaet\AVK1FinanzierungFactory;
use Civi\Funding\SonstigeAktivitaet\AVK1KostenFactory;
use Civi\Funding\SonstigeAktivitaet\AVK1ProjektunterlagenFactory;
use Symfony\Component\DependencyInjection\Reference;

$container->autowire('funding.avk1.application_submit_actions_factory', ApplicationSubmitActionsFactory::class)
  ->setArgument('$actionsDeterminer', new Reference(ReworkPossibleApplicationProcessActionsDeterminer::class))
  ->setArgument('$submitActionsContainer', new Reference('funding.application.submit_actions_container'));

$container->autowire(AVK1JsonSchemaFactory::class)
  ->setArgument('$actionsDeterminer', new Reference(ReworkPossibleApplicationProcessActionsDeterminer::class))
  ->addTag('funding.application.json_schema_factory');
$container->autowire(AVK1UiSchemaFactory::class)
  ->setArgument('$submitActionsFactory', new Reference('funding.avk1.application_submit_actions_factory'))
  ->addTag('funding.application.ui_schema_factory');
$container->autowire(AVK1FormDataFactory::class)
  ->addTag('funding.application.form_data_factory');
$container->autowire(AVK1KostenFactory::class);
$container->autowire(AVK1FinanzierungFactory::class);
$container->autowire(AVK1ProjektunterlagenFactory::class);
$container->autowire(AVK1ApplicationCostItemsFactory::class)
  ->addTag('funding.application.cost_items_factory');
$container->autowire(AVK1ApplicationResourcesItemsFactory::class)
  ->addTag('funding.application.resources_items_factory');
$container->autowire(AVK1ApplicationFormFilesFactory::class)
  ->addTag(AVK1ApplicationFormFilesFactory::SERVICE_TAG);

$container->getDefinition(ReworkPossibleApplicationProcessActionsDeterminer::class)
  ->addTag('funding.application.actions_determiner', ['funding_case_type' => 'AVK1SonstigeAktivitaet']);
$container->getDefinition(ReworkPossibleApplicationProcessStatusDeterminer::class)
  ->addTag('funding.application.status_determiner', ['funding_case_type' => 'AVK1SonstigeAktivitaet']);
$container->getDefinition(ReworkPossibleApplicationProcessActionStatusInfo::class)
  ->addTag('funding.application.action_status_info', ['funding_case_type' => 'AVK1SonstigeAktivitaet']);

$container->getDefinition(DefaultFundingCaseActionsDeterminer::class)
  ->addTag(DefaultFundingCaseActionsDeterminer::TAG, ['funding_case_type' => 'AVK1SonstigeAktivitaet']);
