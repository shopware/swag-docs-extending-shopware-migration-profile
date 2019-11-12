<?php declare(strict_types=1);

namespace SwagMigrationBundleExample\Profile\Shopware\Converter;

use Shopware\Core\Framework\Context;
use SwagMigrationAssistant\Migration\Converter\ConvertStruct;
use SwagMigrationAssistant\Migration\DataSelection\DefaultEntities;
use SwagMigrationAssistant\Migration\MigrationContextInterface;
use SwagMigrationAssistant\Profile\Shopware\Converter\ShopwareConverter;
use SwagMigrationAssistant\Profile\Shopware\ShopwareProfileInterface;
use SwagMigrationBundleExample\Profile\Shopware\DataSelection\DataSet\BundleDataSet;

class BundleConverter extends ShopwareConverter
{
    public function supports(MigrationContextInterface $migrationContext): bool
    {
        return $migrationContext->getProfile() instanceof ShopwareProfileInterface
            && $migrationContext->getDataSet()::getEntity() === BundleDataSet::getEntity();
    }

    public function getSourceIdentifier(array $data): string
    {
        return $data['id'];
    }

    public function convert(array $data, Context $context, MigrationContextInterface $migrationContext): ConvertStruct
    {
        // Generate a checksum for the data to allow faster migrations in the future
        $this->generateChecksum($data);

        $this->mainMapping = $this->mappingService->getOrCreateMapping(
            $migrationContext->getConnection()->getId(),
            BundleDataSet::getEntity(),
            $data['id'],
            $context,
            $this->checksum

        );
        $converted['id'] = $this->mainMapping['entityUuid'];

        $this->convertValue($converted, 'name', $data, 'name');
        $converted['discountType'] = 'absolute';
        $converted['discount'] = 0;

        if (isset($data['products'])) {
            $products = $this->getProducts($context, $migrationContext, $data);

            if (!empty($products)) {
                $converted['products'] = $products;
            }
        }
        unset(
            // Used
            $data['id'],
            $data['name'],
            $data['products']
        );

        if (empty($data)) {
            $data = null;
        }
        $this->updateMainMapping($migrationContext, $context);

        return new ConvertStruct($converted, $data, $this->mainMapping['id']);
    }

    private function getProducts(Context $context, MigrationContextInterface $migrationContext, array $data): array
    {
        $connectionId = $migrationContext->getConnection()->getId();
        $products = [];
        foreach ($data['products'] as $product) {
            $mapping = $this->mappingService->getMapping(
                $connectionId,
                DefaultEntities::PRODUCT . '_mainProduct',
                $product,
                $context
            );

            if ($mapping === null) {
                continue;
            }

            $newProduct['id'] = $mapping['entityUuid'];
            $products[] = $newProduct;
        }

        return $products;
    }

    public function writeMapping(Context $context): void
    {
        $this->mappingService->writeMapping($context);
    }
}
