<?php

declare(strict_types=1);

/*
 * Copyright Iain Cambridge 2020-2022.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: TBD ( 3 years after 2.1.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace Parthenon\Athena\Export;

use Parthenon\Export\Normaliser\NormaliserInterface;

final class NormaliserBuilder implements NormaliserBuilderInterface
{
    public function __construct(private mixed $entity, private array $fields = [])
    {
    }

    public function addField(string $fieldName, string $columnName, \Closure $fieldNormaliser = null)
    {
        if (null === $fieldNormaliser) {
            $fieldNormaliser = function ($value) {
                return $value;
            };
        }

        $this->fields[] = new NormalisedField($fieldName, $columnName, $fieldNormaliser);
    }

    public function getNormaliser(): NormaliserInterface
    {
        return new BuiltNormaliser($this->entity, $this->fields);
    }
}
