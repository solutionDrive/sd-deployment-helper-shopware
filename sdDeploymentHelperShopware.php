<?php

namespace sdDeploymentHelperShopware;

use Shopware\Components\Plugin;
use Symfony\Component\DependencyInjection\ContainerBuilder;

# This vendor/autoload.php is only needed if there are own requirements defined
# in the composer.json and this plugin is installed via zip distribution
/*if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}*/

/**
 * Shopware-Plugin sdDeploymentHelperShopware.
 */
class sdDeploymentHelperShopware extends Plugin
{

    /**
    * @param ContainerBuilder $container
    */
    public function build(ContainerBuilder $container)
    {
        $container->setParameter('sddeploymenthelpershopware.plugin_dir', $this->getPath());
        parent::build($container);
    }
}
