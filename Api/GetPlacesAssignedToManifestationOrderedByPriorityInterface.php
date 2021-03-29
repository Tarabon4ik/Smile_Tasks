<?php

declare(strict_types=1);

namespace Smile\Manifestation\Api;

/**
 * Retrieve places related to current Manifestation ordered by priority
 *
 * Used fully qualified namespaces in annotations for proper work of WebApi request parser
 *
 * @api
 */
interface GetPlacesAssignedToManifestationOrderedByPriorityInterface
{
    /**
     * Get Places assigned to Manifestation ordered by priority
     *
     * If Manifestation with given id doesn't exist then return an empty array
     *
     * @param int $manifestationId
     * @return \Smile\Manifestation\Api\Data\PlaceInterface[]
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute($manifestationId);
}
