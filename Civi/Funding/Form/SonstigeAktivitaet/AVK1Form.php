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

use Civi\Funding\Form\SonstigeAktivitaet\JsonSchema\AVK1JsonSchema;
use Civi\Funding\Form\SonstigeAktivitaet\UISchema\AVK1UiSchema;
use Civi\RemoteTools\Form\JsonForms\Control\JsonFormsHidden;
use Civi\RemoteTools\Form\RemoteForm;
use Civi\RemoteTools\Form\JsonForms\Control\JsonFormsSubmitButton;
use Civi\RemoteTools\Form\JsonSchema\JsonSchemaString;

/**
 * This implements the "AV-K1" form to apply for a funding for a
 * "Sonstige Aktivität" in the "Kinder- und Jugendplan des Bundes (KJP)".
 * Because it is a specific German form strings are not translated.
 */
class AVK1Form extends RemoteForm {

  /**
   * @param string $currency
   * @param array<string, mixed> $data
   * @param array<string, string> $submitActions Map of action names to button labels.
   * @param array<string, \Civi\RemoteTools\Form\JsonSchema\JsonSchema> $hiddenProperties
   */
  public function __construct(string $currency, array $data, array $submitActions, array $hiddenProperties = []) {
    $hiddenFields = [];
    foreach (array_keys($hiddenProperties) as $property) {
      $hiddenFields[] = new JsonFormsHidden('#/properties/' . $property);
    }
    $extraProperties = $hiddenProperties;
    $extraKeywords = ['required' => array_keys($hiddenProperties)];

    $extraProperties['action'] = new JsonSchemaString(['enum' => array_keys($submitActions)]);
    $extraKeywords['required'][] = 'action';
    $submitButtons = [];
    foreach ($submitActions as $name => $label) {
      $submitButtons[] = new JsonFormsSubmitButton('#/properties/action', $label, $name);
    }

    parent::__construct(
      new AVK1JsonSchema($extraProperties, $extraKeywords),
      new AVK1UiSchema($currency, $submitButtons, $hiddenFields),
      $data
    );
  }

}
