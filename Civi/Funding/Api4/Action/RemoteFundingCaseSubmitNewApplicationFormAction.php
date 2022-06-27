<?php
declare(strict_types = 1);

namespace Civi\Funding\Api4\Action;

use Civi\Api4\Generic\Result;
use Civi\Core\CiviEventDispatcher;
use Civi\Funding\Api4\Action\Traits\RemoteFundingActionContactIdRequiredTrait;
use Civi\Funding\Event\FundingEvents;
use Civi\Funding\Event\RemoteFundingCaseSubmitNewApplicationFormEvent;
use Civi\Funding\Remote\RemoteFundingEntityManager;
use Civi\Funding\Remote\RemoteFundingEntityManagerInterface;
use Webmozart\Assert\Assert;

/**
 * @method void setData(array $data)
 */
final class RemoteFundingCaseSubmitNewApplicationFormAction extends AbstractRemoteFundingAction {

  use RemoteFundingActionContactIdRequiredTrait;

  /**
   * @var array<string, mixed>
   * @required
   */
  protected array $data;

  /**
   * @var \Civi\Funding\Remote\RemoteFundingEntityManagerInterface
   */
  private $_remoteFundingEntityManager;

  public function __construct(
    RemoteFundingEntityManagerInterface $remoteFundingEntityManager = NULL,
    CiviEventDispatcher $eventDispatcher = NULL
  ) {
    parent::__construct('RemoteFundingCase', 'submitNewApplicationForm');
    $this->_remoteFundingEntityManager = $remoteFundingEntityManager ?? RemoteFundingEntityManager::getInstance();
    $this->_eventDispatcher = $eventDispatcher ?? \Civi::dispatcher();
    $this->_authorizeRequestEventName = FundingEvents::REMOTE_REQUEST_AUTHORIZE_EVENT_NAME;
    $this->_initRequestEventName = FundingEvents::REMOTE_REQUEST_INIT_EVENT_NAME;
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

    if (NULL === $event->getAction()) {
      throw new \API_Exception('Form not handled');
    }

    $result->rowCount = 1;
    $result->exchangeArray(['action' => $event->getAction()]);
    if (NULL !== $event->getMessage()) {
      $result['message'] = $event->getMessage();
    }

    switch ($event->getAction()) {
      case RemoteFundingCaseSubmitNewApplicationFormEvent::ACTION_SHOW_FORM:
        Assert::notNull($event->getForm());
        $result['jsonSchema'] = $event->getForm()['jsonSchema'];
        $result['uiSchema'] = $event->getForm()['uiSchema'];
        $result['data'] = $event->getForm()['data'];
        break;

      case RemoteFundingCaseSubmitNewApplicationFormEvent::ACTION_SHOW_VALIDATION:
        Assert::notEmpty($event->getErrors());
        $result['errors'] = $event->getErrors();
        break;

      case RemoteFundingCaseSubmitNewApplicationFormEvent::ACTION_CLOSE_FORM:
        break;

      default:
        throw new \API_Exception(sprintf('Unknown action "%s"', $event->getAction()));
    }
  }

  /**
   * @throws \API_Exception
   */
  private function createEvent(): RemoteFundingCaseSubmitNewApplicationFormEvent {
    Assert::notNull($this->remoteContactId);
    $fundingCaseType = $this->_remoteFundingEntityManager
      ->getById('FundingCaseType', $this->getFundingCaseTypeId(), $this->remoteContactId);
    $fundingProgram = $this->_remoteFundingEntityManager
      ->getById('FundingProgram', $this->getFundingProgramId(), $this->remoteContactId);

    return RemoteFundingCaseSubmitNewApplicationFormEvent::fromApiRequest($this, $this->getExtraParams() + [
      'fundingCaseType' => $fundingCaseType,
      'fundingProgram' => $fundingProgram,
    ]);
  }

  public function getFundingProgramId(): int {
    Assert::keyExists($this->data, 'fundingProgramId');
    Assert::integer($this->data['fundingProgramId']);

    return $this->data['fundingProgramId'];
  }

  public function getFundingCaseTypeId(): int {
    Assert::keyExists($this->data, 'fundingCaseTypeId');
    Assert::integer($this->data['fundingCaseTypeId']);

    return $this->data['fundingCaseTypeId'];
  }

}
