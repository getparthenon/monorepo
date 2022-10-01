<?php

declare(strict_types=1);

/*
 * Copyright Humbly Arrogant Ltd 2020-2022.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: 01-10-2025 ( 3 years after 2.0.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace App\Tests;

use App\Kernel;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TestKernel extends Kernel
{
    private static array $parameters = [];

    public static function setParameter(string $name, mixed $value): void
    {
        static::$parameters[$name] = $value;
    }

    public static function resetParameters()
    {
        static::$parameters = [];
    }

    public static function getParameters(): array
    {
        return static::$parameters;
    }

    /**
     * Override this to add parameters to the kernel parameters.
     */
    protected function getKernelParameters(): array
    {
        return array_merge(static::$parameters, parent::getKernelParameters());
    }

    /**
     * Override this to make the container class be dependant on the parameters so that a new cache will be created for the new parameter configurations.
     */
    protected function getContainerClass(): string
    {
        $class = static::class;
        $class = str_contains($class, "@anonymous\0") ? get_parent_class($class).str_replace('.', '_', ContainerBuilder::hash($class)) : $class;
        $class = str_replace('\\', '_', $class).ucfirst($this->environment).($this->debug ? 'Debug' : '').'Container';

        if (!preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $class)) {
            throw new \InvalidArgumentException(sprintf('The environment "%s" contains invalid characters, it can only contain characters allowed in PHP class names.', $this->environment));
        }

        return $class.md5(var_export(static::$parameters, true));
    }
}
