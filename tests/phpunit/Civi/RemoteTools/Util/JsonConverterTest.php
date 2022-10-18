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

namespace Civi\RemoteTools\Util;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Civi\RemoteTools\Util\JsonConverter
 */
final class JsonConverterTest extends TestCase {

  public function testToArray(): void {
    $object = new \stdClass();
    $object->foo = 'bar';

    static::assertSame(['foo' => 'bar'], JsonConverter::toArray($object));
  }

  public function testToStdClass(): void {
    $array = ['foo' => 'bar'];
    $object = JsonConverter::toStdClass($array);

    static::assertSame('bar', $object->foo);
  }

}
