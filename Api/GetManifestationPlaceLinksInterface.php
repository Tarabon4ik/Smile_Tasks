<?php
/**
 * Interface GetManifestationPlaceLinks
 *
 * @category  Smile
 * @package   Smile\Manifestation
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */
declare(strict_types=1);

namespace Smile\Manifestation\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Smile\Manifestation\Api\Data\ManifestationPlaceLinkSearchResultsInterface;

/**
 * Find ManifestationPlaceLink list by SearchCriteria API
 *
 * Used fully qualified namespaces in annotations for proper work of WebApi request parser
 *
 * @api
 */
interface GetManifestationPlaceLinksInterface
{
    /**
     * Find ManifestationPlaceLink list by given SearchCriteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return ManifestationPlaceLinkSearchResultsInterface
     */
    public function execute(
        SearchCriteriaInterface $searchCriteria
    ): ManifestationPlaceLinkSearchResultsInterface;
}
