<?php
/*
 * Copyright (C) 2024 SYSTOPIA GmbH
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

namespace Civi\Funding\ClearingProcess\Form\Container;

use Civi\RemoteTools\JsonSchema\JsonSchema;

/**
 * @template T of \Civi\Funding\Entity\AbstractFinancePlanItemEntity
 */
final class ClearableItems {

  public string $scope;

  public JsonSchema $propertySchema;

  public JsonSchema $financePlanItemSchema;

  /**
   * @phpstan-var array<T>
   */
  public array $items;

  /**
   * @phpstan-param array<T> $items
   */
  public function __construct(
    string $scope,
    JsonSchema $propertySchema,
    JsonSchema $financePlanItemSchema,
    array $items = []
  ) {
    $this->scope = $scope;
    $this->propertySchema = $propertySchema;
    $this->financePlanItemSchema = $financePlanItemSchema;
    $this->items = $items;
  }

}
