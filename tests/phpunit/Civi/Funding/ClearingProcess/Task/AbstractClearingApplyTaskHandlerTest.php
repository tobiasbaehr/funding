<?php
declare(strict_types = 1);

namespace Civi\Funding\ClearingProcess\Task;

use Civi\Funding\ActivityStatusNames;
use Civi\Funding\ClearingProcess\ClearingProcessPermissions;
use Civi\Funding\Entity\FundingTaskEntity;
use Civi\Funding\EntityFactory\ClearingProcessBundleFactory;
use Civi\Funding\EntityFactory\ClearingProcessFactory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Civi\Funding\ClearingProcess\Task\AbstractClearingApplyTaskHandler
 */
final class AbstractClearingApplyTaskHandlerTest extends TestCase {

  private AbstractClearingApplyTaskHandler $taskHandler;

  protected function setUp(): void {
    parent::setUp();
    $this->taskHandler = $this->getMockForAbstractClass(AbstractClearingApplyTaskHandler::class);
  }

  public function testCreateTasksOnChangeAppliable(): void {
    $clearingProcessBundle = ClearingProcessBundleFactory::create(['status' => 'draft']);
    $previousClearingProcess = ClearingProcessFactory::create(['status' => 'not-started']);

    static::assertEquals([
      FundingTaskEntity::newTask([
        'subject' => 'Complete and Apply Clearing',
        'affected_identifier' => $clearingProcessBundle->getApplicationProcess()->getIdentifier(),
        'required_permissions' => [ClearingProcessPermissions::CLEARING_APPLY],
        'type' => 'apply',
        'funding_case_id' => $clearingProcessBundle->getFundingCase()->getId(),
        'application_process_id' => $clearingProcessBundle->getApplicationProcess()->getId(),
        'clearing_process_id' => $clearingProcessBundle->getClearingProcess()->getId(),
      ]),
    ], [...$this->taskHandler->createTasksOnChange($clearingProcessBundle, $previousClearingProcess)]);
  }

  public function testCreateTasksOnChangeNonAppliable(): void {
    $clearingProcessBundle = ClearingProcessBundleFactory::create(['status' => 'review-requested']);
    $previousClearingProcess = ClearingProcessFactory::create(['status' => 'draft']);

    static::assertSame(
      [],
      [...$this->taskHandler->createTasksOnChange($clearingProcessBundle, $previousClearingProcess)]
    );
  }

  public function testCreateTasksOnNew(): void {
    $clearingProcessBundle = ClearingProcessBundleFactory::create();
    static::assertEmpty($this->taskHandler->createTasksOnNew($clearingProcessBundle));
  }

  public function testModifyTaskStatusNotChanged(): void {
    $clearingProcessBundle = ClearingProcessBundleFactory::create([
      'status' => 'draft',
      ['report_data' => ['x' => 'y']],
    ]);
    $previousClearingProcess = ClearingProcessFactory::create([
      'status' => 'draft',
      ['report_data' => ['x' => 'z']],
    ]);

    $task = FundingTaskEntity::newTask([
      'subject' => 'Complete and Apply Clearing',
      'affected_identifier' => $clearingProcessBundle->getApplicationProcess()->getIdentifier(),
      'required_permissions' => [ClearingProcessPermissions::CLEARING_APPLY],
      'type' => 'apply',
      'funding_case_id' => $clearingProcessBundle->getFundingCase()->getId(),
      'application_process_id' => $clearingProcessBundle->getApplicationProcess()->getId(),
      'clearing_process_id' => $clearingProcessBundle->getClearingProcess()->getId(),
    ]);

    static::assertFalse($this->taskHandler->modifyTask($task, $clearingProcessBundle, $previousClearingProcess));
    static::assertSame(ActivityStatusNames::SCHEDULED, $task->getStatusName());
  }

  public function testModifyTaskStatusChanged(): void {
    $clearingProcessBundle = ClearingProcessBundleFactory::create(['status' => 'review-requested']);
    $previousClearingProcess = ClearingProcessFactory::create(['status' => 'draft']);

    $task = FundingTaskEntity::newTask([
      'subject' => 'Complete and Apply Clearing',
      'affected_identifier' => $clearingProcessBundle->getApplicationProcess()->getIdentifier(),
      'required_permissions' => [ClearingProcessPermissions::CLEARING_APPLY],
      'type' => 'apply',
      'funding_case_id' => $clearingProcessBundle->getFundingCase()->getId(),
      'application_process_id' => $clearingProcessBundle->getApplicationProcess()->getId(),
      'clearing_process_id' => $clearingProcessBundle->getClearingProcess()->getId(),
    ]);

    static::assertTrue($this->taskHandler->modifyTask($task, $clearingProcessBundle, $previousClearingProcess));
    static::assertSame(ActivityStatusNames::COMPLETED, $task->getStatusName());
  }

  public function testModifyTaskDifferentTaskType(): void {
    $clearingProcessBundle = ClearingProcessBundleFactory::create(['status' => 'review-requested']);
    $previousClearingProcess = ClearingProcessFactory::create(['status' => 'draft']);

    $task = FundingTaskEntity::newTask([
      'subject' => 'Some Task',
      'affected_identifier' => $clearingProcessBundle->getApplicationProcess()->getIdentifier(),
      'required_permissions' => [ClearingProcessPermissions::CLEARING_APPLY],
      'type' => 'some_type',
      'funding_case_id' => $clearingProcessBundle->getFundingCase()->getId(),
      'application_process_id' => $clearingProcessBundle->getApplicationProcess()->getId(),
      'clearing_process_id' => $clearingProcessBundle->getClearingProcess()->getId(),
    ]);

    static::assertFalse($this->taskHandler->modifyTask($task, $clearingProcessBundle, $previousClearingProcess));
    static::assertSame(ActivityStatusNames::SCHEDULED, $task->getStatusName());
  }

}
