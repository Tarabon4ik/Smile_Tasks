<?php
/**
 * Interface PlaceSearchResult
 *
 * @category  Smile
 * @package   Smile\Manifestation
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */

namespace Smile\Manifestation\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface PlaceSearchResults
 *
 * @api
 */
interface PlaceSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get places
     *
     * @return PlaceInterface[]
     */
    public function getItems();

    /**
     * Set places
     *
     * @param PlaceInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
