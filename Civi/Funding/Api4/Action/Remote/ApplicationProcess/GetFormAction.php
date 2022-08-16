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

namespace Civi\Funding\Api4\Action\Remote\ApplicationProcess;

use Civi\Api4\Generic\Result;
use Civi\Core\CiviEventDispatcher;
use Civi\Funding\Event\Remote\ApplicationProcess\GetFormEvent;
use Civi\Funding\Remote\RemoteFundingEntityManagerInterface;
use Webmozart\Assert\Assert;

/**
 * @method void setApplicationProcessId(int $applicationProcessId)
 */
final class GetFormAction extends AbstractFormAction {

  /**
   * @var int
   * @required
   */
  protected int $applicationProcessId;

  public function __construct(
    RemoteFundingEntityManagerInterface $remoteFundingEntityManager,
    CiviEventDispatcher $eventDispatcher
  ) {
    parent::__construct('getForm', $remoteFundingEntityManager, $eventDispatcher);
  }

  /**
   * @inheritDoc
   *
   * @throws \API_Exception
   */
  public function _run(Result $result): void {
    $event = $this->createEvent();
    $this->dispatchEvent($event);

    $result->debug['event'] = $event->getDebugOutput();
    if (NULL === $event->getJsonSchema() || NULL === $event->getUiSchema()) {
      throw new \API_Exception('Invalid applicationProcessId', 'invalid_arguments');
    }

    Assert::keyExists($event->getData(), 'applicationProcessId');
    Assert::same($event->getData()['applicationProcessId'], $this->applicationProcessId);

    $result->rowCount = 1;
    $result->exchangeArray([
      'jsonSchema' => $event->getJsonSchema(),
      'uiSchema' => $event->getUiSchema(),
      'data' => $event->getData(),
    ]);
  }

  /**
   * @throws \API_Exception
   */
  private function createEvent(): GetFormEvent {
    return GetFormEvent::fromApiRequest($this, $this->createEventParams($this->applicationProcessId));
  }

}
