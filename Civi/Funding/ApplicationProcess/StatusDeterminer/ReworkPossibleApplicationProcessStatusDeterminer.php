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

namespace Civi\Funding\ApplicationProcess\StatusDeterminer;

use Civi\Funding\Entity\FullApplicationProcessStatus;

// phpcs:disable Generic.Files.LineLength.TooLong
final class ReworkPossibleApplicationProcessStatusDeterminer extends AbstractApplicationProcessStatusDeterminerDecorator {
// phpcs:enable
  private const STATUS_ACTION_STATUS_MAP = [
    'eligible' => [
      'request-rework' => 'rework-requested',
      'update' => 'eligible',
    ],
    'rework-requested' => [
      'withdraw-rework-request' => 'eligible',
      'approve-rework-request' => 'rework',
      'reject-rework-request' => 'eligible',
      'add-comment' => 'rework-requested',
    ],
    'rework' => [
      'save' => 'rework',
      'apply' => 'rework-review-requested',
      'withdraw-change' => 'applied',
      'revert-change' => 'applied',
      'review' => 'rework-review',
      'add-comment' => 'rework',
    ],
    'rework-review-requested' => [
      'request-rework' => 'rework',
      'review' => 'rework-review',
      'add-comment' => 'rework-review-requested',
    ],
    'rework-review' => [
      'approve-calculative' => 'rework-review',
      'reject-calculative' => 'rework-review',
      'approve-content' => 'rework-review',
      'reject-content' => 'rework-review',
      'request-change' => 'rework',
      'approve-change' => 'eligible',
      'reject-change' => 'eligible',
      'update' => 'rework-review',
      'add-comment' => 'rework-review',
    ],
    'complete' => [
      'withdraw' => 'withdrawn',
      'update' => 'complete',
      'add-comment' => 'complete',
    ],
  ];

  /**
   * @inheritDoc
   */
  public static function getSupportedFundingCaseTypes(): array {
    return [];
  }

  public function getStatus(FullApplicationProcessStatus $currentStatus, string $action): FullApplicationProcessStatus {
    return isset(self::STATUS_ACTION_STATUS_MAP[$currentStatus->getStatus()][$action])
      ? new FullApplicationProcessStatus(
          self::STATUS_ACTION_STATUS_MAP[$currentStatus->getStatus()][$action],
          $this->getIsReviewCalculative($currentStatus, $action),
          $this->getIsReviewContent($currentStatus, $action)
      ) : parent::getStatus($currentStatus, $action);
  }

  private function getIsReviewCalculative(FullApplicationProcessStatus $currentStatus, string $action): ?bool {
    if ('request-change' === $action) {
      return NULL;
    }

    if ('reject-change' === $action) {
      return TRUE;
    }

    if ('approve-calculative' === $action) {
      return TRUE;
    }

    if ('reject-calculative' === $action) {
      return FALSE;
    }

    return $currentStatus->getIsReviewCalculative();
  }

  private function getIsReviewContent(FullApplicationProcessStatus $currentStatus, string $action): ?bool {
    if ('request-change' === $action) {
      return NULL;
    }

    if ('reject-change' === $action) {
      return TRUE;
    }

    if ('approve-content' === $action) {
      return TRUE;
    }

    if ('reject-content' === $action) {
      return FALSE;
    }

    return $currentStatus->getIsReviewContent();
  }

}
