<?php
declare(strict_types = 1);

namespace Civi\Funding\Task\EventSubscriber;

use Civi\Funding\ActivityTypeNames;
use Civi\Funding\EntityFactory\DrawdownBundleFactory;
use Civi\Funding\EntityFactory\DrawdownFactory;
use Civi\Funding\EntityFactory\FundingCaseTypeFactory;
use Civi\Funding\EntityFactory\FundingTaskFactory;
use Civi\Funding\Event\PayoutProcess\DrawdownCreatedEvent;
use Civi\Funding\Event\PayoutProcess\DrawdownUpdatedEvent;
use Civi\Funding\Task\Creator\DrawdownTaskCreatorInterface;
use Civi\Funding\Task\FundingTaskManager;
use Civi\Funding\Task\Modifier\DrawdownTaskModifierInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Civi\Funding\Task\EventSubscriber\DrawdownTaskSubscriber
 */
final class DrawdownTaskSubscriberTest extends TestCase {

  /**
   * @var \Civi\Funding\Task\EventSubscriber\DrawdownTaskSubscriber
   */
  private DrawdownTaskSubscriber $subscriber;

  /**
   * @var \Civi\Funding\Task\Creator\DrawdownTaskCreatorInterface&\PHPUnit\Framework\MockObject\MockObject
   */
  private MockObject $taskCreatorMock;

  /**
   * @var \Civi\Funding\Task\FundingTaskManager&\PHPUnit\Framework\MockObject\MockObject
   */
  private MockObject $taskManagerMock;

  /**
   * @var \Civi\Funding\Task\Modifier\DrawdownTaskModifierInterface&\PHPUnit\Framework\MockObject\MockObject
   */
  private MockObject $taskModifierMock;

  protected function setUp(): void {
    parent::setUp();
    $this->taskManagerMock = $this->createMock(FundingTaskManager::class);
    $this->taskCreatorMock = $this->createMock(DrawdownTaskCreatorInterface::class);
    $this->taskModifierMock = $this->createMock(DrawdownTaskModifierInterface::class);
    $this->subscriber = new DrawdownTaskSubscriber(
      $this->taskManagerMock,
      [FundingCaseTypeFactory::DEFAULT_NAME => [$this->taskCreatorMock]],
      [FundingCaseTypeFactory::DEFAULT_NAME => [$this->taskModifierMock]]
    );
  }

  public function testGetSubscribedEvents(): void {
    $expectedSubscriptions = [
      DrawdownCreatedEvent::class => 'onCreated',
      DrawdownUpdatedEvent::class => 'onUpdated',
    ];

    static::assertEquals($expectedSubscriptions, $this->subscriber::getSubscribedEvents());

    foreach ($expectedSubscriptions as $method) {
      static::assertTrue(method_exists($this->subscriber, $method));
    }
  }

  public function testOnCreated(): void {
    $drawdownBundle = DrawdownBundleFactory::create();
    $event = new DrawdownCreatedEvent($drawdownBundle);
    $task = FundingTaskFactory::create();

    $this->taskCreatorMock->expects(static::once())->method('createTasksOnNew')
      ->willReturn([$task]);
    $this->taskManagerMock->expects(static::once())->method('addTask')
      ->with($task)
      ->willReturn($task);

    $this->subscriber->onCreated($event);
  }

  public function testOnCreatedWithoutCreators(): void {
    $drawdownBundle = DrawdownBundleFactory::create(
      [],
      [],
      [],
      ['name' => 'SomeCaseType']
    );
    $event = new DrawdownCreatedEvent($drawdownBundle);

    $this->taskCreatorMock->expects(static::never())->method('createTasksOnNew');

    $this->subscriber->onCreated($event);
  }

  public function testOnUpdated(): void {
    $drawdownBundle = DrawdownBundleFactory::create();
    $previousDrawdown = DrawdownFactory::create();
    $event = new DrawdownUpdatedEvent($previousDrawdown, $drawdownBundle);

    $existingTask = FundingTaskFactory::create(['subject' => 'Existing Task']);
    $newTask = FundingTaskFactory::create(['subject' => 'New Task']);

    $this->taskManagerMock->expects(static::once())->method('getOpenTasks')
      ->with(ActivityTypeNames::DRAWDOWN_TASK, $drawdownBundle->getDrawdown()->getId())
      ->willReturn([$existingTask]);
    $this->taskModifierMock->expects(static::once())->method('modifyTask')
      ->with($existingTask, $drawdownBundle, $previousDrawdown)
      ->willReturn(TRUE);
    $this->taskManagerMock->expects(static::once())->method('updateTask')->with($existingTask);

    $this->taskCreatorMock->expects(static::once())->method('createTasksOnChange')
      ->with($drawdownBundle, $previousDrawdown)
      ->willReturn([$newTask]);
    $this->taskManagerMock->expects(static::once())->method('addTask')
      ->with($newTask)
      ->willReturn($newTask);

    $this->subscriber->onUpdated($event);
  }

  public function testOnUpdatedWithoutCreatorsOrModifiers(): void {
    $drawdownBundle = DrawdownBundleFactory::create(
      [],
      [],
      [],
      ['name' => 'SomeCaseType']
    );
    $previousDrawdown = DrawdownFactory::create();
    $event = new DrawdownUpdatedEvent($previousDrawdown, $drawdownBundle);

    $existingTask = FundingTaskFactory::create(['subject' => 'Existing Task']);

    $this->taskManagerMock->expects(static::once())->method('getOpenTasks')
      ->with(ActivityTypeNames::DRAWDOWN_TASK, $drawdownBundle->getDrawdown()->getId())
      ->willReturn([$existingTask]);
    $this->taskModifierMock->expects(static::never())->method('modifyTask');
    $this->taskManagerMock->expects(static::never())->method('updateTask');
    $this->taskCreatorMock->expects(static::never())->method('createTasksOnChange');

    $this->subscriber->onUpdated($event);
  }

}
