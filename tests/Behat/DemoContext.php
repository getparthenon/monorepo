<?php

declare(strict_types=1);

/*
 * Copyright (C) 2020-2024 Iain Cambridge
 *
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace App\Tests\Behat;

use Behat\Behat\Context\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * This context class contains the definitions of the steps used by the demo
 * feature file. Learn how to get started with Behat and BDD on Behat's website.
 *
 * @see http://behat.org/en/latest/quick_start.html
 */
final class DemoContext implements Context
{
    /** @var KernelInterface */
    private $kernel;

    /** @var Response|null */
    private $response;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @When a demo scenario sends a request to :path
     */
    public function aDemoScenarioSendsARequestTo(string $path): void
    {
        $this->response = $this->kernel->handle(Request::create($path, 'GET'));
    }

    /**
     * @Then the response should be received
     */
    public function theResponseShouldBeReceived(): void
    {
        if (null === $this->response) {
            throw new \RuntimeException('No response received');
        }
    }
}
