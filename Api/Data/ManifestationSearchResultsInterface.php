<?php
/**
 * Interface ManifestationSearchResults
 *
 * @category  Smile
 * @package   Smile\Manifestation
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */

namespace Smile\Manifestation\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface ManifestationSearchResults
 *
 * @api
 */
interface ManifestationSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get manifestations
     *
     * @return ManifestationInterface[]
     */
    public function getItems();

    /**
     * Set manifestations
     *
     * @param ManifestationInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
