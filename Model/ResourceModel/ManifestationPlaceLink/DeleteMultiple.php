<?php

declare(strict_types=1);

namespace Smile\Manifestation\Model\ResourceModel\ManifestationPlaceLink;

use Magento\Framework\App\ResourceConnection;
use Smile\Manifestation\Model\ResourceModel\ManifestationPlaceLink as ManifestationPlaceLinkResourceModel;
use Smile\Manifestation\Api\Data\ManifestationPlaceLinkInterface;

/**
 * Implementation of ManifestationPlaceLink delete multiple operation for specific db layer
 * Delete Multiple used here for performance efficient purposes over single delete operation
 */
class DeleteMultiple
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
     * Multiple delete stock source links
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

        $whereSql = $this->buildWhereSqlPart($links);
        $connection->delete($tableName, $whereSql);
    }

    /**
     * Build WHERE part of the delete SQL query
     *
     * @param ManifestationPlaceLinkInterface[] $links
     * @return string
     */
    private function buildWhereSqlPart(array $links): string
    {
        $connection = $this->resourceConnection->getConnection();

        $condition = [];

        foreach ($links as $link) {
            $manifestationCondition = $connection->quoteInto(
                ManifestationPlaceLinkInterface::PLACE_ID . ' = ?',
                $link->getPlaceId()
            );
            $placeCondition = $connection->quoteInto(
                ManifestationPlaceLinkInterface::MANIFESTATION_ID . ' = ?',
                $link->getManifestationId()
            );
            $condition[] = '(' . $manifestationCondition . ' AND ' . $placeCondition . ')';
        }

        return implode(' OR ', $condition);
    }
}
