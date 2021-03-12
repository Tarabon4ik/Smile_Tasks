<?php
/**
 * Interface Manifestation
 *
 * @category  Smile
 * @package   Smile\Manifestation
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */

namespace Smile\Manifestation\Api\Data;

/**
 * Interface ManifestationInterface
 *
 * @api
 */
interface ManifestationInterface
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY = 'manifestation';

    /**#@+
     * Constants defined for data array
     */
    const ID = 'entity_id';
    const TITLE = 'title';
    const DESCRIPTION = 'description';
    const META_TITLE = 'meta_title';
    const META_DESCRIPTION = 'meta_description';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const START_DATE = 'start_date';
    const END_DATE = 'end_date';
    const IS_NEED_ELECTRICITY = 'is_need_electricity';
    const IS_NEED_WATER = 'is_need_water';
    const IMAGE = 'image';
    const PLACE_IDS = 'place_ids';
    /**#@-*/

    /**
     * Manifestation id
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set Manifestation id
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Manifestation title
     *
     * @return string
     */
    public function getTitle();

    /**
     * Set manifestation title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title);

    /**
     * Manifestation description
     *
     * @return string|null
     */
    public function getDescription();

    /**
     * Set manifestation description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description);

    /**
     * Manifestation meta title
     *
     * @return string|null
     */
    public function getMetaTitle();

    /**
     * Set manifestation meta title
     *
     * @param string $metaTitle
     * @return $this
     */
    public function setMetaTitle($metaTitle);

    /**
     * Manifestation meta description
     *
     * @return string|null
     */
    public function getMetaDescription();

    /**
     * Set manifestation meta description
     *
     * @param string $metaDescription
     * @return $this
     */
    public function setMetaDescription($metaDescription);

    /**
     * Manifestation created date
     *
     * @return string
     */
    public function getCreatedAt();

    /**
     * Set manifestation created date
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt);

    /**
     * Manifestation updated date
     *
     * @return string
     */
    public function getUpdatedAt();

    /**
     * Set manifestation updated date
     *
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt);

    /**
     * Manifestation start date
     *
     * @return string
     */
    public function getStartDate();

    /**
     * Set manifestation start date
     *
     * @param string $startDate
     * @return $this
     */
    public function setStartDate($startDate);

    /**
     * Manifestation end date
     *
     * @return string
     */
    public function getEndDate();

    /**
     * Set manifestation end date
     *
     * @param string $endDate
     * @return $this
     */
    public function setEndDate($endDate);

    /**
     * Check whether manifestation is need electricity
     *
     * @return bool
     */
    public function getIsNeedElectricity();

    /**
     * Set whether manifestation is need electricity
     *
     * @param bool $isNeedElectricity
     * @return $this
     */
    public function setIsNeedElectricity($isNeedElectricity);

    /**
     * Check whether manifestation is need water
     *
     * @return bool
     */
    public function getIsNeedWater();

    /**
     * Set whether manifestation is need water
     *
     * @param bool $isNeedWater
     * @return $this
     */
    public function setIsNeedWater($isNeedWater);

    /**
     * Get media gallery entries
     *
     * @return string|null
     */
    public function getImage();

    /**
     * Set media gallery entries
     *
     * @param string $image
     * @return $this
     */
    public function setImage($image);

    /**
     * Get Place Ids
     *
     * @return array|null
     */
    public function getPlaceIds();

    /**
     * Set Place Ids
     *
     * @param array $placeIds
     * @return $this
     */
    public function setPlaceIds($placeIds);

}
