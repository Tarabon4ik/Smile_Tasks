<?php

declare(strict_types=1);

namespace Smile\Manifestation\Api;

/**
 * Service method for manifestation place links delete multiple
 * Performance efficient API
 *
 * Used fully qualified namespaces in annotations for proper work of WebApi request parser
 *
 * @api
 */
interface ManifestationPlaceLinksDeleteInterface
{
    /**
     * Remove StockSourceLink list list
     *
     * @param \Smile\Manifestation\Api\Data\ManifestationPlaceLinkInterface[] $links
     * @return void
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function execute(array $links): void;
}
