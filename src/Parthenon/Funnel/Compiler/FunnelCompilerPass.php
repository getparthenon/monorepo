<?php

declare(strict_types=1);

/*
 * Copyright Iain Cambridge 2020-2022.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: 01-10-2025 ( 3 years after 2.0.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace Parthenon\Funnel\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class FunnelCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $this->handleRepositories($container);
        $this->handleActions($container);
    }

    private function handleRepositories(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('parthenon.funnel.repository.repository_manager')) {
            return;
        }

        $filterManager = $container->getDefinition('parthenon.funnel.repository.repository_manager');
        $definitions = $container->findTaggedServiceIds('parthenon.funnel.repository');

        foreach ($definitions as $name => $defintion) {
            $filterManager->addMethodCall('addRepository', [new Reference($name)]);
        }
    }

    private function handleActions(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('parthenon.funnel.unfinnished_actions.action_manager')) {
            return;
        }

        $filterManager = $container->getDefinition('parthenon.funnel.unfinnished_actions.action_manager');
        $definitions = $container->findTaggedServiceIds('parthenon.funnel.action');

        foreach ($definitions as $name => $defintion) {
            $filterManager->addMethodCall('addAction', [new Reference($name)]);
        }
    }
}
