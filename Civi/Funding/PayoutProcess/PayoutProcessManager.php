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

namespace Civi\Funding\PayoutProcess;

use Civi\Api4\FundingDrawdown;
use Civi\Api4\FundingPayoutProcess;
use Civi\Core\CiviEventDispatcherInterface;
use Civi\Funding\Entity\FundingCaseEntity;
use Civi\Funding\Entity\PayoutProcessEntity;
use Civi\Funding\Event\PayoutProcess\PayoutProcessCreatedEvent;
use Civi\RemoteTools\Api4\Api4Interface;
use Civi\RemoteTools\Api4\Query\Comparison;

class PayoutProcessManager {

  private Api4Interface $api4;

  private CiviEventDispatcherInterface $eventDispatcher;

  public function __construct(Api4Interface $api4, CiviEventDispatcherInterface $eventDispatcher) {
    $this->api4 = $api4;
    $this->eventDispatcher = $eventDispatcher;
  }

  public function create(FundingCaseEntity $fundingCase, float $amountTotal): PayoutProcessEntity {
    $result = $this->api4->createEntity(FundingPayoutProcess::_getEntityName(), [
      'funding_case_id' => $fundingCase->getId(),
      'status' => 'open',
      'amount_total' => $amountTotal,
      'amount_paid_out' => 0.0,
    ], [
      'checkPermissions' => FALSE,
    ]);

    $payoutProcess = PayoutProcessEntity::singleFromApiResult($result);

    $event = new PayoutProcessCreatedEvent($fundingCase, $payoutProcess);
    $this->eventDispatcher->dispatch(PayoutProcessCreatedEvent::class, $event);

    return $payoutProcess;
  }

  public function get(int $id): ?PayoutProcessEntity {
    $result = $this->api4->getEntities(
      FundingPayoutProcess::_getEntityName(),
      Comparison::new('id', '=', $id),
      [],
      1,
      0,
      ['checkPermissions' => FALSE],
    );

    return PayoutProcessEntity::singleOrNullFromApiResult($result);
  }

  public function getAmountAvailable(PayoutProcessEntity $payoutProcess): float {
    return $payoutProcess->getAmountTotal() - $this->getAmountRequested($payoutProcess);
  }

  public function getAmountRequested(PayoutProcessEntity $payoutProcess): float {
    $action = FundingDrawdown::get()
      ->setCheckPermissions(FALSE)
      ->addSelect('SUM(amount) AS amountSum')
      ->addWhere('payout_process_id', '=', $payoutProcess->getId());

    return $this->api4->executeAction($action)->first()['amountSum'] ?? 0.0;
  }

  public function getLastByFundingCaseId(int $fundingCaseId): ?PayoutProcessEntity {
    $result = $this->api4->getEntities(
      FundingPayoutProcess::_getEntityName(),
      Comparison::new('funding_case_id', '=', $fundingCaseId),
      ['id' => 'DESC'],
      1,
      0,
      ['checkPermissions' => FALSE],
    );

    return PayoutProcessEntity::singleOrNullFromApiResult($result);
  }

  public function hasAccess(int $id): bool {
    return $this->api4->countEntities(
      FundingPayoutProcess::_getEntityName(),
      Comparison::new('id', '=', $id),
      ['checkPermissions' => FALSE],
    ) === 1;
  }

}
