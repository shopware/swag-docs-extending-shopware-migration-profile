<?php declare(strict_types=1);

namespace SwagMigrationBundleExample;

use Shopware\Core\Framework\Plugin;
use Swag\BundleExample\BundleExample;
use SwagMigrationAssistant\SwagMigrationAssistant;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class SwagMigrationBundleExample extends Plugin
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $activePlugins = $container->getParameter('kernel.active_plugins');
        if (!isset($activePlugins[SwagMigrationAssistant::class])) {
            throw new \Exception('The plugin SwagMigrationAssistant is required, please install and activate it first');
        }

        if (!isset($activePlugins[BundleExample::class])) {
            throw new \Exception('The plugin SwagBundleExample is required, please install and activate it first');
        }

        // Only load migration relevant classes if the SwagMigrationAssistant is available
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/DependencyInjection/'));
        $loader->load('migration_assistant_extension.xml');
    }
}
