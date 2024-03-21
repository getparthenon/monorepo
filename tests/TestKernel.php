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
