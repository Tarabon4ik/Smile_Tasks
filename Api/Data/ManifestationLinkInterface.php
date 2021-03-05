<?php
/**
 * Interface ManifestationLink
 *
 * @category  Smile
 * @package   Smile\Manifestation
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */

namespace Smile\Manifestation\Api\Data;

/**
 * Interface ManifestationLink
 *
 * @api
 */
interface ManifestationLinkInterface
{
    /**#@+
     * Constants defined for data array
     */
    const ID = 'entity_id';
    const MANIFESTATION_ID = 'manifestation_id';
    const PLACE_ID = 'place_id';
    /**#@-*/

    /**
     * ManifestationLink id
     *
     * @return int
     */
    public function getId();

    /**
     * Set ManifestationLink id
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * ManifestationLink manifestationId
     *
     * @return int
     */
    public function getManifestationId();

    /**
     * Set ManifestationLink manifestationId
     *
     * @param int $manifestationId
     * @return $this
     */
    public function setManifestationId($manifestationId);

    /**
     * ManifestationLink placeId
     *
     * @return int
     */
    public function getPlaceId();

    /**
     * Set ManifestationLink placeId
     *
     * @param int $placeId
     * @return $this
     */
    public function setPlaceId($placeId);
}
