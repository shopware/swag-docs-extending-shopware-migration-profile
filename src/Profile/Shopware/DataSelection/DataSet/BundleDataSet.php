<?php declare(strict_types=1);

namespace SwagMigrationBundleExample\Profile\Shopware\DataSelection\DataSet;

use SwagMigrationAssistant\Migration\DataSelection\DataSet\CountingInformationStruct;
use SwagMigrationAssistant\Migration\DataSelection\DataSet\CountingQueryStruct;
use SwagMigrationAssistant\Migration\MigrationContextInterface;
use SwagMigrationAssistant\Profile\Shopware\DataSelection\DataSet\ShopwareDataSet;
use SwagMigrationAssistant\Profile\Shopware\ShopwareProfileInterface;

class BundleDataSet extends ShopwareDataSet
{
    public static function getEntity(): string
    {
        return 'swag_bundle';
    }

    public function supports(MigrationContextInterface $migrationContext): bool
    {
        return $migrationContext->getProfile() instanceof ShopwareProfileInterface;
    }

    public function getCountingInformation(): ?CountingInformationStruct
    {
        $information = new CountingInformationStruct(self::getEntity());
        $information->addQueryStruct(new CountingQueryStruct('s_bundles'));

        return $information;
    }

    public function getApiRoute(): string
    {
        return 'SwagMigrationBundles';
    }

    public function getExtraQueryParameters(): array
    {
        return [];
    }
}
