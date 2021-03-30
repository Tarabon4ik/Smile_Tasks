<?php
/**
 * Interface GetPlacesAssignedToManifestationOrderedByPriority
 *
 * @category  Smile
 * @package   Smile\Manifestation
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */
declare(strict_types=1);

namespace Smile\Manifestation\Api;

use Smile\Manifestation\Api\Data\PlaceInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;

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
     * @return PlaceInterface[]
     * @throws InputException
     * @throws LocalizedException
     */
    public function execute($manifestationId);
}
