<?php
/*
 * Copyright (C) 2025 SYSTOPIA GmbH
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

namespace Civi\Funding\FundingCaseTypes\AuL\SonstigeAktivitaet\Application\UISchema;

use Civi\RemoteTools\JsonForms\JsonFormsControl;
use Civi\RemoteTools\JsonForms\Layout\JsonFormsGroup;

final class AVK1OeffentlicheMittelUiSchema extends JsonFormsGroup {

  public function __construct(string $currency) {
    $elements = [
      new JsonFormsControl(
        '#/properties/finanzierung/properties/oeffentlicheMittel/properties/europa',
        'Finanzierung durch Europa-Mittel in ' . $currency
      ),
      new JsonFormsControl(
        '#/properties/finanzierung/properties/oeffentlicheMittel/properties/bundeslaender',
        'Finanzierung durch Bundesländer in ' . $currency
      ),
      new JsonFormsControl(
        '#/properties/finanzierung/properties/oeffentlicheMittel/properties/staedteUndKreise',
        'Finanzierung durch Städte und Kreise in ' . $currency
      ),
    ];

    parent::__construct(
      'Öffentliche Mittel',
      $elements,
      'Bitte geben Sie weitere Finanzierungen an.'
    );
  }

}
