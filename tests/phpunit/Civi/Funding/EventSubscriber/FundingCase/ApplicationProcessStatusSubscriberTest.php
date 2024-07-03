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

namespace Civi\Funding\EventSubscriber\FundingCase;

use Civi\Funding\EntityFactory\ApplicationProcessBundleFactory;
use Civi\Funding\EntityFactory\ApplicationProcessFactory;
use Civi\Funding\EntityFactory\FundingCaseTypeFactory;
use Civi\Funding\Event\ApplicationProcess\ApplicationProcessUpdatedEvent;
use Civi\Funding\FundingCase\FundingCaseManager;
use Civi\Funding\FundingCase\StatusDeterminer\FundingCaseStatusDeterminerInterface;
use Civi\Funding\FundingCaseTypeServiceLocator;
use Civi\Funding\FundingCaseTypeServiceLocatorContainer;
use Civi\Funding\Mock\Psr\PsrContainer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Civi\Funding\EventSubscriber\FundingCase\ApplicationProcessStatusSubscriber
 */
final class ApplicationProcessStatusSubscriberTest extends TestCase {

  /**
   * @var \Civi\Funding\FundingCase\FundingCaseManager&\PHPUnit\Framework\MockObject\MockObject
   */
  private MockObject $fundingCaseManagerMock;

  /**
   * @var \Civi\Funding\FundingCase\StatusDeterminer\FundingCaseStatusDeterminerInterface&\PHPUnit\Framework\MockObject\MockObject
   */
  private MockObject $statusDeterminerMock;

  private ApplicationProcessStatusSubscriber $subscriber;

  protected function setUp(): void {
    parent::setUp();
    $this->fundingCaseManagerMock = $this->createMock(FundingCaseManager::class);
    $this->statusDeterminerMock = $this->createMock(FundingCaseStatusDeterminerInterface::class);
    $serviceLocatorContainer = new FundingCaseTypeServiceLocatorContainer(new PsrContainer([
      FundingCaseTypeFactory::DEFAULT_NAME => new FundingCaseTypeServiceLocator(new PsrContainer([
        FundingCaseStatusDeterminerInterface::class => $this->statusDeterminerMock,
      ])),
    ]));
    $this->subscriber = new ApplicationProcessStatusSubscriber(
      $this->fundingCaseManagerMock,
      $serviceLocatorContainer,
    );
  }

  public function testGetSubscribedEvents(): void {
    $expectedSubscriptions = [
      ApplicationProcessUpdatedEvent::class => 'onUpdated',
    ];

    static::assertEquals($expectedSubscriptions, ApplicationProcessStatusSubscriber::getSubscribedEvents());

    foreach ($expectedSubscriptions as $method) {
      static::assertTrue(method_exists(ApplicationProcessStatusSubscriber::class, $method));
    }
  }

  public function testOnUpdatedStatusChanged(): void {
    $event = $this->createEvent('foo', 'new_status');

    $this->statusDeterminerMock->expects(static::once())->method('getStatusOnApplicationProcessStatusChange')
      ->with($event->getApplicationProcessBundle(), 'previous_status')
      ->willReturn('bar');

    $this->fundingCaseManagerMock->expects(static::once())->method('update')
      ->with($event->getFundingCase());

    $this->subscriber->onUpdated($event);
    static::assertSame('bar', $event->getFundingCase()->getStatus());
  }

  public function testOnUpdatedStatusUnchanged(): void {
    $event = $this->createEvent('foo', 'new_status');

    $this->statusDeterminerMock->expects(static::once())->method('getStatusOnApplicationProcessStatusChange')
      ->with($event->getApplicationProcessBundle(), 'previous_status')
      ->willReturn('foo');

    $this->fundingCaseManagerMock->expects(static::never())->method('update');

    $this->subscriber->onUpdated($event);
    static::assertSame('foo', $event->getFundingCase()->getStatus());
  }

  private function createEvent(
    string $fundingCaseStatus,
    string $applicationProcessStatus
  ): ApplicationProcessUpdatedEvent {
    return new ApplicationProcessUpdatedEvent(
      11,
      ApplicationProcessFactory::createApplicationProcess(['status' => 'previous_status']),
      ApplicationProcessBundleFactory::createApplicationProcessBundle(
        ['status' => $applicationProcessStatus],
        ['status' => $fundingCaseStatus]
      ),
    );
  }

}
