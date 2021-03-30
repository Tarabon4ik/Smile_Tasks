<?php
/**
 * SearchResults ManifestationPlaceLink
 *
 * @category  Smile
 * @package   Smile\Manifestation
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */
declare(strict_types=1);

namespace Smile\Manifestation\Model;

use Magento\Framework\Api\SearchResults;
use Smile\Manifestation\Api\Data\ManifestationPlaceLinkSearchResultsInterface;

class ManifestationPlaceLinkSearchResults extends SearchResults implements ManifestationPlaceLinkSearchResultsInterface
{
}
