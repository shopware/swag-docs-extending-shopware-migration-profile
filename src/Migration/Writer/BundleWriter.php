<?php declare(strict_types=1);

namespace SwagMigrationBundleExample\Migration\Writer;

use SwagMigrationAssistant\Migration\Writer\AbstractWriter;
use SwagMigrationBundleExample\Profile\Shopware\DataSelection\DataSet\BundleDataSet;

class BundleWriter extends AbstractWriter
{
    public function supports(): string
    {
        return BundleDataSet::getEntity();
    }
}
