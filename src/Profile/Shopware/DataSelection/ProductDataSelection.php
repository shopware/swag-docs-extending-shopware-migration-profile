<?php declare(strict_types=1);

namespace SwagMigrationBundleExample\Profile\Shopware\DataSelection;

use SwagMigrationAssistant\Migration\DataSelection\DataSelectionInterface;
use SwagMigrationAssistant\Migration\DataSelection\DataSelectionStruct;
use SwagMigrationAssistant\Migration\MigrationContextInterface;
use SwagMigrationBundleExample\Profile\Shopware\DataSelection\DataSet\BundleDataSet;
use SwagMigrationOwnProfileExample\Profile\OwnProfile\DataSelection\DataSet\ProductDataSet;

class ProductDataSelection implements DataSelectionInterface
{
    /**
     * @var DataSelectionInterface
     */
    private $originalDataSelection;

    public function __construct(DataSelectionInterface $originalDataSelection)
    {
        $this->originalDataSelection = $originalDataSelection;
    }

    public function supports(MigrationContextInterface $migrationContext): bool
    {
        return $this->originalDataSelection->supports($migrationContext);
    }

    public function getData(): DataSelectionStruct
    {
        $dataSelection = $this->originalDataSelection->getData();

        return new DataSelectionStruct(
            $dataSelection->getId(),
            $this->getEntityNames(),
            $this->getEntityNamesRequiredForCount(),
            $dataSelection->getSnippet(),
            $dataSelection->getPosition(),
            $dataSelection->getProcessMediaFiles(),
            DataSelectionStruct::PLUGIN_DATA_TYPE
        );
    }

    /**
     * @return string[]
     */
    public function getEntityNames(): array
    {
        $entities = $this->originalDataSelection->getEntityNames();
        $entities[] = BundleDataSet::getEntity();

        return $entities;
    }

    public function getEntityNamesRequiredForCount(): array
    {
        return [
            ProductDataSet::getEntity(),
        ];
    }
}
