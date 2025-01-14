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

// phpcs:disable Drupal.Commenting.DocComment.ContentAfterOpen
/** @var \Symfony\Component\DependencyInjection\ContainerBuilder $container */

use Civi\Api4\Generic\AbstractAction;
use Civi\Funding\DependencyInjection\Util\ServiceRegistrator;
use Civi\Funding\TransferContract\Handler\DefaultTransferContractRenderHandler;
use Civi\Funding\TransferContract\Handler\TransferContractRenderHandlerInterface;
use Civi\Funding\TransferContract\TransferContractCreator;
use Civi\RemoteTools\ActionHandler\ActionHandlerInterface;

$container->autowire(TransferContractCreator::class);
$container->autowire(TransferContractRenderHandlerInterface::class, DefaultTransferContractRenderHandler::class);

ServiceRegistrator::autowireAllImplementing(
  $container,
  __DIR__ . '/../Civi/Funding/Api4/Action/FundingTransferContract',
  'Civi\\Funding\\Api4\\Action\\FundingTransferContract',
  AbstractAction::class,
  [],
  [
    'public' => TRUE,
    'shared' => FALSE,
  ]
);

ServiceRegistrator::autowireAllImplementing(
  $container,
  __DIR__ . '/../Civi/Funding/TransferContract/Api4/ActionHandler',
  'Civi\\Funding\\TransferContract\\Api4\\ActionHandler',
  ActionHandlerInterface::class,
  [ActionHandlerInterface::SERVICE_TAG => []],
);
