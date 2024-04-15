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

namespace Civi\Funding\SammelantragKurs\Application\UiSchema;

use Civi\RemoteTools\JsonForms\Layout\JsonFormsCategorization;
use Civi\RemoteTools\JsonForms\Layout\JsonFormsGroup;

final class KursApplicationUiSchema extends JsonFormsGroup {

  /**
   * @phpstan-param array<int, \Civi\RemoteTools\JsonForms\Control\JsonFormsSubmitButton> $submitButtons
   */
  public function __construct(string $title, string $currency, array $submitButtons) {
    $elements = [
      new JsonFormsCategorization([
        new KursGrunddatenUiSchema(),
        new KursFinanzierungUiSchema('#/properties/finanzierung/properties', $currency),
        new KursZuschussUiSchema($currency),
        new KursBeschreibungUiSchema(),
      ]),
      ...$submitButtons,
    ];
    parent::__construct($title, $elements);
  }

}
