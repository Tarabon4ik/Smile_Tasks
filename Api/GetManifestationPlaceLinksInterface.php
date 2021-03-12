<?php

declare(strict_types=1);

namespace Smile\Manifestation\Api;

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
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Smile\Manifestation\Api\Data\ManifestationPlaceLinkSearchResultsInterface
     */
    public function execute(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    ): \Smile\Manifestation\Api\Data\ManifestationPlaceLinkSearchResultsInterface;
}
