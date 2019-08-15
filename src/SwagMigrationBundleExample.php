<?php declare(strict_types=1);

namespace SwagMigrationBundleExample;

use Shopware\Core\Framework\Plugin;
use Shopware\Core\Kernel;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class SwagMigrationBundleExample extends Plugin
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $migrationPlugin = Kernel::getPlugins()->get(\SwagMigrationAssistant\SwagMigrationAssistant::class);
        if ($migrationPlugin === null || $migrationPlugin->isActive() === false) {
            return;
        }

        // Only load migration relevant classes if the SwagMigrationAssistant is available
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/DependencyInjection/'));
        $loader->load('migration_assistant_extension.xml');
    }
}
