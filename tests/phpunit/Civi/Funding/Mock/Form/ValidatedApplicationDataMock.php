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

namespace Civi\Funding\Mock\Form;

use Civi\Funding\Form\Application\ValidatedApplicationDataInterface;

/**
 * @phpstan-type mockValidatedDataT array<string, mixed>&array{
 *   _action: string,
 *   title: string,
 *   shortDescription: string,
 *   recipientContactId: int,
 *   startDate: string,
 *   endDate: string,
 *   amountRequested: float,
 *   costItemsData: array<string, \Civi\Funding\ApplicationProcess\JsonSchema\CostItem\CostItemData>,
 *   resourcesItemsData: array<string, \Civi\Funding\ApplicationProcess\JsonSchema\ResourcesItem\ResourcesItemData>,
 *   comment?: array{text: string, type: string},
 * }
 *
 * @phpstan-type mockValidatedDataValuesT array{
 *   _action?: string,
 *   title?: string,
 *   shortDescription?: string,
 *   recipientContactId?: int,
 *   startDate?: string,
 *   endDate?: string,
 *   amountRequested?: float,
 *   costItemsData?: array<string, \Civi\Funding\ApplicationProcess\JsonSchema\CostItem\CostItemData>,
 *   resourcesItemsData?: array<string, \Civi\Funding\ApplicationProcess\JsonSchema\ResourcesItem\ResourcesItemData>,
 *   comment?: array{text: string, type: string},
 * }
 */
final class ValidatedApplicationDataMock implements ValidatedApplicationDataInterface {

  public const ACTION = 'ValidatedAction';

  public const TITLE = 'Validated Title';

  public const SHORT_DESCRIPTION = 'Validated short description';

  public const RECIPIENT_CONTACT_ID = 4711;

  public const START_DATE = '2022-10-04 01:02:03';

  public const END_DATE = '2022-11-04 02:03:04';

  public const AMOUNT_REQUESTED = 987.65;

  public const APPLICATION_DATA = ['foo' => 'bar'];

  /**
   * @phpstan-var mockValidatedDataT
   */
  private array $data;

  /**
   * @phpstan-var array<string, mixed>
   */
  private array $applicationData = [];

  /**
   * @phpstan-var array<string, mixed>
   */
  private array $mappedData;

  /**
   * @phpstan-param array<string, mixed> $applicationData
   * @phpstan-param mockValidatedDataValuesT $data
   * @phpstan-param array<string, mixed> $mappedData
   */
  public function __construct(
    array $applicationData = self::APPLICATION_DATA,
    array $data = [],
    array $mappedData = []
  ) {
    $this->applicationData = $applicationData;
    $this->data = $data + [
      '_action' => self::ACTION,
      'title' => self::TITLE,
      'shortDescription' => self::SHORT_DESCRIPTION,
      'recipientContactId' => self::RECIPIENT_CONTACT_ID,
      'startDate' => self::START_DATE,
      'endDate' => self::END_DATE,
      'amountRequested' => self::AMOUNT_REQUESTED,
      'costItemsData' => [],
      'resourcesItemsData' => [],
    ];
    $this->mappedData = $mappedData;
  }

  public function getAction(): string {
    return $this->data['_action'];
  }

  public function getTitle(): string {
    return $this->data['title'];
  }

  public function getShortDescription(): string {
    return $this->data['shortDescription'];
  }

  public function getRecipientContactId(): int {
    return $this->data['recipientContactId'];
  }

  public function getStartDate(): \DateTimeInterface {
    return new \DateTime($this->data['startDate']);
  }

  public function getEndDate(): \DateTimeInterface {
    return new \DateTime($this->data['endDate']);
  }

  public function getAmountRequested(): float {
    return $this->data['amountRequested'];
  }

  /**
   * @inheritDoc
   */
  public function getCostItemsData(): array {
    return $this->data['costItemsData'];
  }

  /**
   * @inheritDoc
   */
  public function getResourcesItemsData(): array {
    return $this->data['resourcesItemsData'];
  }

  public function getComment(): ?array {
    return $this->data['comment'] ?? NULL;
  }

  public function getMappedData(): array {
    return $this->mappedData;
  }

  /**
   * @phpstan-param array<string, mixed> $mappedData
   */
  public function setMappedData(array $mappedData): self {
    $this->mappedData = $mappedData;

    return $this;
  }

  public function getApplicationData(): array {
    return $this->applicationData;
  }

  public function getRawData(): array {
    return $this->data;
  }

}
