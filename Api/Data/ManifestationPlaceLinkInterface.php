<?php
/**
 * Interface ManifestationPlaceLink
 *
 * @category  Smile
 * @package   Smile\Manifestation
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */
declare(strict_types=1);

namespace Smile\Manifestation\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Represents relation between Manifestation and Place entities.
 *
 * Used fully qualified namespaces in annotations for proper work of WebApi request parser
 *
 * @api
 */
interface ManifestationPlaceLinkInterface extends ExtensibleDataInterface
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY = 'manifestation_place_link';

    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const LINK_ID = 'link_id';
    const MANIFESTATION_ID = 'manifestation_id';
    const PLACE_ID = 'place_id';
    const PRIORITY = 'priority';
    /**#@-*/

    /**
     * Get link id
     *
     * @return int
     */
    public function getLinkId();

    /**
     * Set link id
     *
     * @param int $linkId
     * @return void
     */
    public function setLinkId($linkId);

    /**
     * Get manifestation id
     *
     * @return int
     */
    public function getManifestationId();

    /**
     * Set manifestation id
     *
     * @param int $manifestationId
     * @return void
     */
    public function setManifestationId($manifestationId);

    /**
     * Get Place Id of the manifestation
     *
     * @return int
     */
    public function getPlaceId();

    /**
     * Set Place Id of the manifestation
     *
     * @param int $placeId
     *
     * @return void
     */
    public function setPlaceId($placeId);

    /**
     * Get priority of the link
     *
     * @return int|null
     */
    public function getPriority();

    /**
     * Set priority of the link
     *
     * @param int $priority
     *
     * @return void
     */
    public function setPriority($priority);
}
