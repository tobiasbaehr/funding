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

namespace Civi\Funding;

use Civi\Funding\Entity\AttachmentEntity;
use Civi\RemoteTools\Api3\Api3Interface;
use Civi\RemoteTools\Api4\Api4Interface;

final class FundingAttachmentManager implements FundingAttachmentManagerInterface {

  private Api3Interface $api3;

  private Api4Interface $api4;

  public function __construct(
    Api3Interface $api3,
    Api4Interface $api4
  ) {
    $this->api3 = $api3;
    $this->api4 = $api4;
  }

  /**
   * @inheritDoc
   */
  public function attachFile(
    string $entityTable,
    int $entityId,
    string $filename,
    string $mimeType,
    array $optional = []
  ): AttachmentEntity {
    $values = [
      'entity_table' => $entityTable,
      'entity_id' => $entityId,
      'name' => $optional['name'] ?? basename($filename),
      // mime_type is required by Attachment entity.
      'mime_type' => $mimeType,
      'description' => $optional['description'] ?? NULL,
      'created_id' => $optional['created_id'] ?? NULL,
      'file_type_id' => $optional['file_type_id'] ?? NULL,
      'options' => [
        'move-file' => $filename,
      ],
      'sequential' => 1,
      // Ensure path is returned.
      'check_permissions' => FALSE,
    ];

    $result = $this->api3->execute('Attachment', 'create', $values);
    /** @phpstan-var array<string, mixed> $resultValues */
    // @phpstan-ignore-next-line
    $resultValues = $result['values'][0];

    $fileUpdateValues = [];
    // Attachment API ignores file_type_id.
    if ($values['file_type_id'] !== ($resultValues['file_type_id'] ?? NULL)) {
      $fileUpdateValues['file_type_id'] = $values['file_type_id'];
    }
    // Attachment API returns created_id, but does not use provided value.
    if (isset($values['created_id']) && $values['created_id'] !== $resultValues['created_id']) {
      $fileUpdateValues['created_id'] = $values['created_id'];
    }

    if ([] !== $fileUpdateValues) {
      // @phpstan-ignore-next-line
      $this->api4->updateEntity('File', (int) $resultValues['id'], $fileUpdateValues, ['checkPermissions' => FALSE]);
      $resultValues = array_merge($resultValues, $fileUpdateValues);
    }

    // @phpstan-ignore-next-line
    return AttachmentEntity::fromApi3Values($resultValues);
  }

  /**
   * @inheritDoc
   */
  public function delete(AttachmentEntity $attachment): void {
    $this->api3->execute('Attachment', 'delete', [
      'id' => $attachment->getId(),
      'entity_table' => $attachment->getEntityTable(),
      'entity_id' => $attachment->getEntityId(),
    ]);
  }

  public function get(int $id, string $entityTable, int $entityId): ?AttachmentEntity {
    /** @phpstan-var array{count: int, values: array<int, array<string, mixed>>} $result */
    $result = $this->api3->execute('Attachment', 'get', [
      'id' => $id,
      'entity_table' => $entityTable,
      'entity_id' => $entityId,
      'sequential' => 1,
      // Ensure path is returned.
      'check_permissions' => FALSE,
    ]);

    return $this->createFirstAttachmentFromResultOrNull($result);
  }

  /**
   * @inheritDoc
   */
  public function getLastByFileType(string $entityTable, int $entityId, int $fileTypeId): ?AttachmentEntity {
    /** @phpstan-var array{count: int, values: array<int, array<string, mixed>>} $result */
    $result = $this->api3->execute('Attachment', 'get', [
      'entity_table' => $entityTable,
      'entity_id' => $entityId,
      'file_type_id' => $fileTypeId,
      'sequential' => 1,
      'options' => ['sort' => ['id DESC']],
      // Ensure path is returned.
      'check_permissions' => FALSE,
    ]);

    // Attachment API ignores file_type_id.
    foreach ($result['values'] as $values) {
      // @phpstan-ignore-next-line
      if ($this->getFileTypeIdForFile((int) $values['id']) === $fileTypeId) {
        // @phpstan-ignore-next-line
        return AttachmentEntity::fromApi3Values($values);
      }
    }

    return NULL;
  }

  public function has(string $entityTable, int $entityId, int $fileTypeId): bool {
    return NULL !== $this->getLastByFileType($entityTable, $entityId, $fileTypeId);
  }

  /**
   * @inheritDoc
   */
  public function update(AttachmentEntity $attachment): void {
    // Attachment API has no update action, so we have to directly use File.
    $this->api4->updateEntity(
      'File',
      $attachment->getId(),
      $attachment->toArray(),
      ['checkPermissions' => FALSE],
    );
  }

  /**
   * @phpstan-param array{count: int, values: array<int, array<string, mixed>>} $result
   */
  private function createFirstAttachmentFromResultOrNull(array $result): ?AttachmentEntity {
    // @phpstan-ignore-next-line
    return $result['count'] > 0 ? AttachmentEntity::fromApi3Values($result['values'][0]) : NULL;
  }

  /**
   * @throws \CRM_Core_Exception
   */
  private function getFileTypeIdForFile(int $fileId): ?int {
    $action = $this->api4->createGetAction('File')
      ->setCheckPermissions(FALSE)
      ->addSelect('file_type_id')
      ->addWhere('id', '=', $fileId);

    return $this->api4->executeAction($action)->first()['file_type_id'] ?? NULL;
  }

}
