<?php
/**
 * Model ManifestationSearchResults
 *
 * @category  Smile
 * @package   Smile\Manifestation
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */

declare(strict_types=1);

namespace Smile\Manifestation\Model;

use Smile\Manifestation\Api\Data\ManifestationSearchResultsInterface;
use Magento\Framework\Api\SearchResults;

/**
 * Service Data Object with Manifestation search results.
 */
class ManifestationSearchResults extends SearchResults implements ManifestationSearchResultsInterface
{
}
