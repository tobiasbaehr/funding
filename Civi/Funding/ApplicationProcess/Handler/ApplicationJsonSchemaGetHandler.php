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

namespace Civi\Funding\ApplicationProcess\Handler;

use Civi\Funding\ApplicationProcess\Command\ApplicationJsonSchemaGetCommand;
use Civi\Funding\ApplicationProcess\Helper\ApplicationJsonSchemaCreateHelper;
use Civi\Funding\Form\Application\ApplicationJsonSchemaFactoryInterface;
use Civi\RemoteTools\JsonSchema\JsonSchema;

final class ApplicationJsonSchemaGetHandler implements ApplicationJsonSchemaGetHandlerInterface {

  private ApplicationJsonSchemaCreateHelper $jsonSchemaCreateHelper;

  private ApplicationJsonSchemaFactoryInterface $jsonSchemaFactory;

  public function __construct(
    ApplicationJsonSchemaCreateHelper $jsonSchemaCreateHelper,
    ApplicationJsonSchemaFactoryInterface $jsonSchemaFactory
  ) {
    $this->jsonSchemaCreateHelper = $jsonSchemaCreateHelper;
    $this->jsonSchemaFactory = $jsonSchemaFactory;
  }

  public function handle(ApplicationJsonSchemaGetCommand $command): JsonSchema {
    $jsonSchema = $this->jsonSchemaFactory->createJsonSchemaExisting(
      $command->getApplicationProcessBundle(),
      $command->getApplicationProcessStatusList(),
    );

    $this->jsonSchemaCreateHelper->addActionProperty(
      $jsonSchema,
      $command->getApplicationProcessBundle(),
      $command->getApplicationProcessStatusList()
    );
    $this->jsonSchemaCreateHelper->addCommentProperty($jsonSchema, $command->getApplicationProcessBundle());
    $this->jsonSchemaCreateHelper->addReadOnlyKeywordIfNecessary(
      $jsonSchema,
      $command->getApplicationProcessBundle(),
      $command->getApplicationProcessStatusList()
    );

    return $jsonSchema;
  }

}
