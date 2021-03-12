<?php

declare(strict_types=1);

namespace Smile\Manifestation\Model\ResourceModel\ManifestationPlaceLink;

use Magento\Framework\App\ResourceConnection;
use Smile\Manifestation\Model\ResourceModel\ManifestationPlaceLink as ManifestationPlaceLinkResourceModel;
use Smile\Manifestation\Model\ManifestationPlaceLink;
use Smile\Manifestation\Api\Data\ManifestationPlaceLinkInterface;

/**
 * Implementation of ManifestationPlaceLink save multiple operation for specific db layer
 * Save Multiple used here for performance efficient purposes over single save operation
 */
class SaveMultiple
{
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        ResourceConnection $resourceConnection
    ) {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * Multiple save StockSourceLinks
     *
     * @param ManifestationPlaceLinkInterface[] $links
     * @return void
     */
    public function execute(array $links)
    {
        if (!count($links)) {
            return;
        }
        $connection = $this->resourceConnection->getConnection();
        $tableName = $this->resourceConnection->getTableName(
            ManifestationPlaceLinkResourceModel::TABLE_NAME
        );

        $columnsSql = $this->buildColumnsSqlPart([
            ManifestationPlaceLink::PLACE_ID,
            ManifestationPlaceLink::MANIFESTATION_ID,
            ManifestationPlaceLink::PRIORITY,
        ]);
        $valuesSql = $this->buildValuesSqlPart($links);
        $onDuplicateSql = $this->buildOnDuplicateSqlPart([
            ManifestationPlaceLink::PRIORITY,
        ]);
        $bind = $this->getSqlBindData($links);

        $insertSql = sprintf(
            'INSERT INTO `%s` (%s) VALUES %s %s',
            $tableName,
            $columnsSql,
            $valuesSql,
            $onDuplicateSql
        );
        $connection->query($insertSql, $bind);
    }

    /**
     * @param array $columns
     * @return string
     */
    private function buildColumnsSqlPart(array $columns): string
    {
        $connection = $this->resourceConnection->getConnection();
        $processedColumns = array_map([$connection, 'quoteIdentifier'], $columns);
        return implode(', ', $processedColumns);
    }

    /**
     * @param ManifestationPlaceLinkInterface[] $links
     * @return string
     */
    private function buildValuesSqlPart(array $links): string
    {
        $sql = rtrim(str_repeat('(?, ?, ?), ', count($links)), ', ');
        return $sql;
    }

    /**
     * @param ManifestationPlaceLinkInterface[] $links
     * @return array
     */
    private function getSqlBindData(array $links): array
    {
        $bind = [];
        foreach ($links as $link) {
            $bind = array_merge($bind, [
                $link->getPlaceId(),
                $link->getManifestationId(),
                $link->getPriority(),
            ]);
        }
        return $bind;
    }

    /**
     * @param array $fields
     * @return string
     */
    private function buildOnDuplicateSqlPart(array $fields): string
    {
        $connection = $this->resourceConnection->getConnection();
        $processedFields = [];

        foreach ($fields as $field) {
            $processedFields[] = sprintf('%1$s = VALUES(%1$s)', $connection->quoteIdentifier($field));
        }
        return 'ON DUPLICATE KEY UPDATE ' . implode(', ', $processedFields);
    }
}
