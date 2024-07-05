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

namespace Civi\Funding\Form\Validation;

use Civi\RemoteTools\Form\RemoteFormInterface;
use Civi\RemoteTools\JsonSchema\Validation\ValidationResultInterface;
use Civi\RemoteTools\JsonSchema\Validation\ValidatorInterface;

final class FormValidator implements FormValidatorInterface {

  private ValidatorInterface $validator;

  public function __construct(ValidatorInterface $validator) {
    $this->validator = $validator;
  }

  public function validate(RemoteFormInterface $form): ValidationResultInterface {
    return $this->validator->validate($form->getJsonSchema(), $form->getData());
  }

}
