<?php

declare(strict_types=1);

/*
 * Copyright (C) 2020-2025 Iain Cambridge
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU LESSER GENERAL PUBLIC LICENSE as published by
 * the Free Software Foundation, either version 2.1 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace Parthenon\Athena;

use Parthenon\Athena\Exception\NoSectionOpenException;
use Parthenon\Athena\Exception\SectionAlreadyOpenException;
use Parthenon\Athena\Read\Section;

final class ReadView
{
    private ViewTypeManagerInterface $viewTypeManager;

    /**
     * @var Section[]
     */
    private array $sections = [];

    private ?Section $openSection = null;

    public function __construct(ViewTypeManagerInterface $viewTypeManager)
    {
        $this->viewTypeManager = $viewTypeManager;
    }

    public function section(string $name, ?string $controller = null): self
    {
        if (!is_null($this->openSection)) {
            throw new SectionAlreadyOpenException();
        }

        $this->sections[] = $this->openSection = new Section($name, $controller);

        return $this;
    }

    public function field(string $name, $type = 'text'): self
    {
        if (is_null($this->openSection)) {
            throw new NoSectionOpenException();
        }

        $this->openSection->addField(new Field($name, $this->viewTypeManager->get($type)));

        return $this;
    }

    public function end(): self
    {
        $this->openSection = null;

        return $this;
    }

    public function getSections(): array
    {
        return $this->sections;
    }
}
