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

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface ManifestationInterface
 *
 * @api
 */
interface ManifestationInterface extends ExtensibleDataInterface
{
    /**#@+
     * Constants defined for data array
     */
    const MANIFESTATION_ID = 'entity_id';
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
     * @param int|null $id
     * @return void
     */
    public function setId($id): void;

    /**
     * Manifestation title
     *
     * @return string|null
     */
    public function getTitle(): ?string;

    /**
     * Set manifestation title
     *
     * @param string|null $title
     * @return void
     */
    public function setTitle(?string $title): void;

    /**
     * Manifestation description
     *
     * @return string|null
     */
    public function getDescription(): ?string;

    /**
     * Set manifestation description
     *
     * @param string|null $description
     * @return void
     */
    public function setDescription(?string $description): void;

    /**
     * Manifestation meta title
     *
     * @return string|null
     */
    public function getMetaTitle(): ?string;

    /**
     * Set manifestation meta title
     *
     * @param string|null $metaTitle
     * @return void
     */
    public function setMetaTitle(?string $metaTitle): void;

    /**
     * Manifestation meta description
     *
     * @return string|null
     */
    public function getMetaDescription(): ?string;

    /**
     * Set manifestation meta description
     *
     * @param string|null $metaDescription
     * @return void
     */
    public function setMetaDescription(?string $metaDescription): void;

    /**
     * Manifestation created date
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string;

    /**
     * Set manifestation created date
     *
     * @param string|null $createdAt
     * @return void
     */
    public function setCreatedAt(?string $createdAt): void;

    /**
     * Manifestation updated date
     *
     * @return string|null
     */
    public function getUpdatedAt(): ?string;

    /**
     * Set manifestation updated date
     *
     * @param string|null $updatedAt
     * @return void
     */
    public function setUpdatedAt(?string$updatedAt): void;

    /**
     * Manifestation start date
     *
     * @return string|null
     */
    public function getStartDate(): ?string;

    /**
     * Set manifestation start date
     *
     * @param string|null $startDate
     * @return void
     */
    public function setStartDate(?string $startDate): void;

    /**
     * Manifestation end date
     *
     * @return string|null
     */
    public function getEndDate(): ?string;

    /**
     * Set manifestation end date
     *
     * @param string|null $endDate
     * @return void
     */
    public function setEndDate(?string $endDate): void;

    /**
     * Check whether manifestation is need electricity
     *
     * @return bool|null
     */
    public function getIsNeedElectricity(): ?bool;

    /**
     * Set whether manifestation is need electricity
     *
     * @param bool|null $isNeedElectricity
     * @return void
     */
    public function setIsNeedElectricity(?bool $isNeedElectricity): void;

    /**
     * Check whether manifestation is need water
     *
     * @return bool|null
     */
    public function getIsNeedWater(): ?bool;

    /**
     * Set whether manifestation is need water
     *
     * @param bool|null $isNeedWater
     * @return void
     */
    public function setIsNeedWater(?bool $isNeedWater): void;

    /**
     * Get media gallery entries
     *
     * @return string|null
     */
    public function getImage(): ?string;

    /**
     * Set media gallery entries
     *
     * @param string|null $image
     * @return void
     */
    public function setImage(?string $image): void;
}
