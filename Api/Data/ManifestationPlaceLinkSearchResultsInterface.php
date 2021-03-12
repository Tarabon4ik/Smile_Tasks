<?php

declare(strict_types=1);

namespace Smile\Manifestation\Api\Data;

/**
 * Search results of Repository::getList method
 *
 * Used fully qualified namespaces in annotations for proper work of WebApi request parser
 *
 * @api
 */
interface ManifestationPlaceLinkSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get ManifestationPlaceLink list
     *
     * @return \Smile\Manifestation\Api\Data\ManifestationPlaceLinkInterface[]
     */
    public function getItems();

    /**
     * Set ManifestationPlaceLink list
     *
     * @param \Smile\Manifestation\Api\Data\ManifestationPlaceLinkInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}
