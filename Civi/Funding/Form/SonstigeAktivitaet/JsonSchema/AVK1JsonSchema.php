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

namespace Civi\Funding\Form\SonstigeAktivitaet\JsonSchema;

use Civi\Funding\Form\JsonSchema\JsonSchemaRecipient;
use Civi\RemoteTools\Form\JsonSchema\JsonSchemaDataPointer;
use Civi\RemoteTools\Form\JsonSchema\JsonSchemaDate;
use Civi\RemoteTools\Form\JsonSchema\JsonSchemaObject;
use Civi\RemoteTools\Form\JsonSchema\JsonSchemaString;
use Webmozart\Assert\Assert;

/**
 * This implements the JSON schema for an "AV-K1" form to apply for a funding
 * for a "Sonstige Aktivität" in the "Kinder- und Jugendplan des Bundes (KJP)".
 * Because it is a specific German form strings are not translated.
 */
final class AVK1JsonSchema extends JsonSchemaObject {

  /**
   * @phpstan-param array<int, string> $possibleRecipients
   *   Map of contact IDs to names.
   * @phpstan-param array<string, \Civi\RemoteTools\Form\JsonSchema\JsonSchema> $extraProperties
   */
  public function __construct(\DateTimeInterface $applicationBegin, \DateTimeInterface $applicationEnd,
    array $possibleRecipients, array $extraProperties = [], array $keywords = []
  ) {
    // TODO: Additional validations (required, length, min, max, ...)
    $required = $keywords['required'] ?? [];
    Assert::isArray($required);
    $keywords['required'] = array_merge([
      'titel',
      'kurzbezeichnungDesInhalts',
      'empfaenger',
      'beginn',
      'ende',
      'kosten',
      'finanzierung',
    ], $required);

    parent::__construct([
      'titel' => new JsonSchemaString(),
      'kurzbezeichnungDesInhalts' => new JsonSchemaString(),
      'empfaenger' => new JsonSchemaRecipient($possibleRecipients),
      'beginn' => new JsonSchemaDate([
        'minDate' => $applicationBegin->format('Y-m-d'),
        'maxDate' => $applicationEnd->format('Y-m-d'),
      ]),
      'ende' => new JsonSchemaDate([
        'minDate' => new JsonSchemaDataPointer('1/beginn', '0000-00-00'),
        'maxDate' => $applicationEnd->format('Y-m-d'),
      ]),
      // Abschnitt I
      'kosten' => new AVK1KostenSchema(),
      // Abschnitt II
      'finanzierung' => new AVK1FinanzierungSchema(),
      'file' => new JsonSchemaString(['format' => 'uri']),
    ] + $extraProperties, $keywords);
  }

}
