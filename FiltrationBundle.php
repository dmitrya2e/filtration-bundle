<?php

namespace Da2e\FiltrationBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Da2e\FiltrationBundle\DependencyInjection\Compiler\FiltrationCompilePass;

/**
 * Class FiltrationBundle
 * @package Da2e\FiltrationBundle
 */
class FiltrationBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new FiltrationCompilePass());
    }
}
