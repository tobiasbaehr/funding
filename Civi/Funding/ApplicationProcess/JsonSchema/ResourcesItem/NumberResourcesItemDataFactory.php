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

namespace Civi\Funding\ApplicationProcess\JsonSchema\ResourcesItem;

use Opis\JsonSchema\Exceptions\ParseException;
use Opis\JsonSchema\JsonPointer;
use Opis\JsonSchema\Parsers\SchemaParser;
use Opis\JsonSchema\ValidationContext;

/**
 * ResourcesItemData factory for numbers/integers.
 *
 * @phpstan-type clearingT array{itemLabel: string}
 */
final class NumberResourcesItemDataFactory {

  /**
   * @phpstan-var non-empty-string
   */
  private string $type;

  /**
   * @phpstan-var non-empty-string
   */
  private string $identifier;

  /**
   * @phpstan-var clearingT|null
   */
  private ?array $clearing;

  /**
   * @param \stdClass $resourcesItemSchema
   *   The resources item keyword data for a property of type number or integer.
   *
   * @throws \Opis\JsonSchema\Exceptions\ParseException
   */
  public static function parse(\stdClass $resourcesItemSchema, SchemaParser $parser): self {
    if (!self::propertyIsNonEmptyString($resourcesItemSchema, 'identifier')) {
      throw new ParseException('identifier is required');
    }

    if (0 === preg_match('/^[a-zA-Z0-9.\-_]+$/', $resourcesItemSchema->identifier)) {
      throw new ParseException('identifier may only contain letters, numbers, ".", "-", and "_"');
    }

    if (!self::propertyIsNonEmptyString($resourcesItemSchema, 'type')) {
      throw new ParseException('type must be a non empty string');
    }

    return new self(
      $resourcesItemSchema->type,
      $resourcesItemSchema->identifier,
      self::parseClearing($resourcesItemSchema)
    );
  }

  /**
   * @phpstan-return clearingT|null
   *
   * @throws \Opis\JsonSchema\Exceptions\ParseException
   */
  private static function parseClearing(\stdClass $data): ?array {
    if (!property_exists($data, 'clearing')) {
      return NULL;
    }

    if (!self::propertyIsNonEmptyString($data->clearing, 'itemLabel')) {
      throw new ParseException('If clearing is enabled, an item label has to be set');
    }

    return [
      'itemLabel' => $data->clearing->itemLabel,
    ];
  }

  private static function propertyIsNonEmptyString(\stdClass $data, string $propertyName): bool {
    return property_exists($data, $propertyName)
      && is_string($data->{$propertyName})
      && '' !== $data->{$propertyName};
  }

  /**
   * @phpstan-param non-empty-string $type
   * @phpstan-param non-empty-string $identifier
   * @phpstan-param clearingT|null $clearing
   *   NULL if no clearing is required.
   */
  private function __construct(
    string $type,
    string $identifier,
    ?array $clearing
  ) {
    $this->type = $type;
    $this->identifier = $identifier;
    $this->clearing = $clearing;
  }

  /**
   * @throws \InvalidArgumentException
   */
  public function createResourcesItemData(ValidationContext $context): ?ResourcesItemData {
    $dataType = $context->currentDataType();
    if ('null' === $dataType) {
      return NULL;
    }

    if ('number' !== $dataType && 'integer' !== $dataType) {
      throw new \InvalidArgumentException(
        sprintf('Expected data type object got "%s"', $dataType)
      );
    }

    $amount = $context->currentData();
    if (!is_float($amount) && !is_int($amount)) {
      throw new \InvalidArgumentException('Data is not a number');
    }

    /** @phpstan-var non-empty-string $dataPointer */
    $dataPointer = JsonPointer::pathToString($context->currentDataPath());

    return new ResourcesItemData([
      'type' => $this->type,
      'identifier' => $this->identifier,
      'amount' => (float) $amount,
      'properties' => [],
      'clearing' => $this->clearing,
      'dataPointer' => $dataPointer,
      'dataType' => $dataType,
    ]);
  }

}
