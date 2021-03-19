<?php

declare(strict_types=1);

namespace Smile\Manifestation\Api;

/**
 * Service method for ManifestationPlace links save multiple
 * Performance efficient API
 *
 * Used fully qualified namespaces in annotations for proper work of WebApi request parser
 *
 * @api
 */
interface ManifestationPlaceLinksSaveInterface
{
    /**
     * Save ManifestationPlaceLink list data
     *
     * @param \Smile\Manifestation\Api\Data\ManifestationPlaceLinkInterface[] $links
     * @return void
     * @throws \Magento\Framework\Validation\ValidationException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function execute(array $links): void;
}
