<?php
/**
 * Interface ManifestationPlaceLinkSearchResults
 *
 * @category  Smile
 * @package   Smile\Manifestation
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */
declare(strict_types=1);

namespace Smile\Manifestation\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;
use \Smile\Manifestation\Api\Data\ManifestationPlaceLinkInterface;

/**
 * Search results of Repository::getList method
 *
 * Used fully qualified namespaces in annotations for proper work of WebApi request parser
 *
 * @api
 */
interface ManifestationPlaceLinkSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get ManifestationPlaceLink list
     *
     * @return ManifestationPlaceLinkInterface[]
     */
    public function getItems();

    /**
     * Set ManifestationPlaceLink list
     *
     * @param ManifestationPlaceLinkInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}
