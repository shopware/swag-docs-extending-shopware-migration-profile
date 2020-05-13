<?php declare(strict_types=1);

namespace SwagMigrationBundleExample\Profile\Shopware\DataSelection\DataSet;

use SwagMigrationAssistant\Migration\DataSelection\DataSet\DataSet;
use SwagMigrationAssistant\Migration\MigrationContextInterface;
use SwagMigrationAssistant\Profile\Shopware\ShopwareProfileInterface;

class BundleDataSet extends DataSet
{
    public static function getEntity(): string
    {
        return 'swag_bundle';
    }

    public function supports(MigrationContextInterface $migrationContext): bool
    {
        return $migrationContext->getProfile() instanceof ShopwareProfileInterface;
    }
}
