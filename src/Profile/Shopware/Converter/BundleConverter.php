<?php declare(strict_types=1);

namespace SwagMigrationBundleExample\Profile\Shopware\Converter;

use Shopware\Core\Framework\Context;
use SwagMigrationAssistant\Migration\Converter\ConvertStruct;
use SwagMigrationAssistant\Migration\DataSelection\DefaultEntities;
use SwagMigrationAssistant\Migration\Mapping\MappingServiceInterface;
use SwagMigrationAssistant\Migration\MigrationContextInterface;
use SwagMigrationAssistant\Profile\Shopware\Converter\ShopwareConverter;
use SwagMigrationAssistant\Profile\Shopware\ShopwareProfileInterface;
use SwagMigrationBundleExample\Profile\Shopware\DataSelection\DataSet\BundleDataSet;

class BundleConverter extends ShopwareConverter
{
    /**
     * @var MappingServiceInterface
     */
    private $mappingService;

    public function __construct(MappingServiceInterface $mappingService) {
        $this->mappingService = $mappingService;
    }

    public function supports(MigrationContextInterface $migrationContext): bool
    {
        return $migrationContext->getProfile() instanceof ShopwareProfileInterface
            && $migrationContext->getDataSet()::getEntity() === BundleDataSet::getEntity();
    }

    public function convert(array $data, Context $context, MigrationContextInterface $migrationContext): ConvertStruct
    {
        $converted = [];
        $converted['id'] = $this->mappingService->createNewUuid(
            $migrationContext->getConnection()->getId(),
            BundleDataSet::getEntity(),
            $data['id'],
            $context
        );
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

        return new ConvertStruct($converted, $data);
    }

    private function getProducts(Context $context, MigrationContextInterface $migrationContext, array $data): array
    {
        $connectionId = $migrationContext->getConnection()->getId();
        $products = [];
        foreach ($data['products'] as $product) {
            $productUuid = $this->mappingService->getUuid($connectionId, DefaultEntities::PRODUCT . '_mainProduct', $product, $context);

            if ($productUuid === null) {
                continue;
            }

            $newProduct['id'] = $productUuid;
            $products[] = $newProduct;
        }

        return $products;
    }

    public function writeMapping(Context $context): void
    {
        $this->mappingService->writeMapping($context);
    }
}