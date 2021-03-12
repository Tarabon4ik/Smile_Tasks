<?php
/**
 * Model PlaceSearchResults
 *
 * @category  Smile
 * @package   Smile\Manifestation
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */

declare(strict_types=1);

namespace Smile\Manifestation\Model;

use Smile\Manifestation\Api\Data\PlaceSearchResultsInterface;
use Magento\Framework\Api\SearchResults;

/**
 * Service Data Object with Place search results.
 */
class PlaceSearchResults extends SearchResults implements PlaceSearchResultsInterface
{
}
