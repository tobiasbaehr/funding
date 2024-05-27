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

use Civi\Funding\ApplicationProcess\JsonSchema\CostItem\JsonSchemaCostItem;
use Civi\Funding\ApplicationProcess\JsonSchema\ResourcesItem\JsonSchemaResourcesItem;
use Civi\RemoteTools\JsonSchema\JsonSchemaDate;
use Civi\RemoteTools\JsonSchema\JsonSchemaInteger;
use Civi\RemoteTools\JsonSchema\JsonSchemaMoney;
use Civi\RemoteTools\JsonSchema\JsonSchemaObject;
use Civi\RemoteTools\JsonSchema\JsonSchemaString;
use Webmozart\Assert\Assert;

final class TestJsonSchema extends JsonSchemaObject {

  /**
   * @phpstan-param array<string, \Civi\RemoteTools\JsonSchema\JsonSchema> $extraProperties
   */
  public function __construct(array $extraProperties = [], array $keywords = []) {
    $required = $keywords['required'] ?? [];
    Assert::isArray($required);
    $keywords['required'] = array_merge([
      'title',
      'recipient',
      'startDate',
      'endDate',
      'amountRequested',
      'resources',
      'file',
    ], $required);

    parent::__construct([
      'title' => new JsonSchemaString(),
      'shortDescription' => new JsonSchemaString(['default' => 'Default description']),
      'recipient' => new JsonSchemaInteger(),
      'startDate' => new JsonSchemaDate(),
      'endDate' => new JsonSchemaDate(),
      'amountRequested' => new JsonSchemaMoney([
        '$costItem' => new JsonSchemaCostItem([
          'type' => 'amount',
          'identifier' => 'amountRequested',
          'clearing' => [
            'itemLabel' => 'Amount requested',
          ],
        ]),
      ]),
      'resources' => new JsonSchemaMoney([
        '$resourcesItem' => new JsonSchemaResourcesItem([
          'type' => 'testResources',
          'identifier' => 'resources',
          'clearing' => [
            'itemLabel' => 'Test Resources',
          ],
        ]),
      ]),
      'file' => new JsonSchemaString(['format' => 'uri']),
    ] + $extraProperties, $keywords);
  }

}
