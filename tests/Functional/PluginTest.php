<?php

namespace sdDeploymentHelperShopware\Tests;

use sdDeploymentHelperShopware\sdDeploymentHelperShopware as Plugin;
use Shopware\Components\Test\Plugin\TestCase;

class PluginTest extends TestCase
{
    protected static $ensureLoadedPlugins = [
        'sdDeploymentHelperShopware' => []
    ];

    public function testCanCreateInstance()
    {
        /** @var Plugin $plugin */
        $plugin = Shopware()->Container()->get('kernel')->getPlugins()['sdDeploymentHelperShopware'];

        $this->assertInstanceOf(Plugin::class, $plugin);
    }
}
