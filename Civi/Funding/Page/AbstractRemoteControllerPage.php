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

namespace Civi\Funding\Page;

use Civi\Funding\Event\Remote\RemotePageRequestEvent;
use Civi\RemoteTools\RequestContext\RequestContextInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @codeCoverageIgnore
 *
 * phpcs:disable Generic.NamingConventions.AbstractClassNamePrefix.Missing
 */
abstract class AbstractRemoteControllerPage extends AbstractControllerPage {

  protected function handle(Request $request): Response {
    /** @var \Civi\RemoteTools\RequestContext\RequestContextInterface $requestContext */
    $requestContext = \Civi::service(RequestContextInterface::class);
    $requestContext->setRemote(TRUE);
    $event = new RemotePageRequestEvent($this, $request);
    \Civi::dispatcher()->dispatch(RemotePageRequestEvent::class, $event);

    return parent::handle($request);
  }

}
