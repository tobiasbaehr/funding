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

namespace Civi\Funding\DocumentRender\Token;

use Psr\SimpleCache\CacheInterface;

/**
 * @codeCoverageIgnore
 */
final class TokenNameExtractorCacheDecorator implements TokenNameExtractorInterface {

  private TokenNameExtractorInterface $extractor;

  private CacheInterface $cache;

  public function __construct(TokenNameExtractorInterface $extractor, CacheInterface $cache) {
    $this->extractor = $extractor;
    $this->cache = $cache;
  }

  /**
   * @inheritDoc
   */
  public function getTokenNames(string $entityName, string $entityClass): array {
    $cacheKey = 'funding.token_names.' . $entityName . ':' . $entityClass;
    if ($this->cache->has($cacheKey)) {
      // @phpstan-ignore-next-line
      return $this->cache->get($cacheKey);
    }

    $tokenNames = $this->extractor->getTokenNames($entityName, $entityClass);
    $this->cache->set($cacheKey, $tokenNames);

    return $tokenNames;
  }

}
