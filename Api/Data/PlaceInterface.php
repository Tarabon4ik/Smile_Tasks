<?php

declare(strict_types=1);

namespace Smile\Manifestation\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Represents physical place for manifestations
 *
 * Used fully qualified namespaces in annotations for proper work of WebApi request parser
 *
 * @api
 */
interface PlaceInterface extends ExtensibleDataInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const PLACE_ID = 'entity_id';
    const NAME = 'name';
    const CONTACT_NAME = 'contact_name';
    const ENABLED = 'enabled';
    const EMAIL = 'email';
    const DESCRIPTION = 'description';
    const LATITUDE = 'latitude';
    const LONGITUDE = 'longitude';
    const COUNTRY_ID = 'country_id';
    const REGION_ID = 'region_id';
    const REGION = 'region';
    const CITY = 'city';
    const STREET = 'street';
    const PHONE = 'phone';
    const IMAGE = 'image';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * Get place id
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set place id
     *
     * @param int|null $entityId
     * @return void
     */
    public function setId($entityId): void;

    /**
     * Get place name
     *
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * Set place name
     *
     * @param string|null $name
     * @return void
     */
    public function setName(?string $name): void;

    /**
     * Get place email
     *
     * @return string|null
     */
    public function getEmail(): ?string;

    /**
     * Set place email
     *
     * @param string|null $email
     * @return void
     */
    public function setEmail(?string $email): void;

    /**
     * Get place contact name
     *
     * @return string|null
     */
    public function getContactName(): ?string;

    /**
     * Set place contact name
     *
     * @param string|null $contactName
     * @return void
     */
    public function setContactName(?string $contactName): void;

    /**
     * Check if place is enabled. For new entity can be null
     *
     * @return bool|null
     */
    public function isEnabled(): ?bool;

    /**
     * Enable or disable place
     *
     * @param bool|null $enabled
     * @return void
     */
    public function setEnabled(?bool $enabled): void;

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


    /**
     * Get place description
     *
     * @return string|null
     */
    public function getDescription(): ?string;

    /**
     * Set place description
     *
     * @param string|null $description
     * @return void
     */
    public function setDescription(?string $description): void;

    /**
     * Get place latitude
     *
     * @return float|null
     */
    public function getLatitude(): ?float;

    /**
     * Set place latitude
     *
     * @param float|null $latitude
     * @return void
     */
    public function setLatitude(?float $latitude): void;

    /**
     * Get place longitude
     *
     * @return float|null
     */
    public function getLongitude(): ?float;

    /**
     * Set place longitude
     *
     * @param float|null $longitude
     * @return void
     */
    public function setLongitude(?float $longitude): void;

    /**
     * Get place country id
     *
     * @return string|null
     */
    public function getCountryId(): ?string;

    /**
     * Set place country id
     *
     * @param string|null $countryId
     * @return void
     */
    public function setCountryId(?string $countryId): void;

    /**
     * Get region id if place has registered region.
     *
     * @return int|null
     */
    public function getRegionId(): ?int;

    /**
     * Set region id if place has registered region.
     *
     * @param int|null $regionId
     * @return void
     */
    public function setRegionId(?int $regionId): void;

    /**
     * Get region title if place has custom region
     *
     * @return string|null
     */
    public function getRegion(): ?string;

    /**
     * Set place region title
     *
     * @param string|null $region
     * @return void
     */
    public function setRegion(?string $region): void;

    /**
     * Get place city
     *
     * @return string|null
     */
    public function getCity(): ?string;

    /**
     * Set place city
     *
     * @param string|null $city
     * @return void
     */
    public function setCity(?string $city): void;

    /**
     * Get place street name
     *
     * @return string|null
     */
    public function getStreet(): ?string;

    /**
     * Set place street name
     *
     * @param string|null $street
     * @return void
     */
    public function setStreet(?string $street): void;

    /**
     * Get place phone number
     *
     * @return string|null
     */
    public function getPhone(): ?string;

    /**
     * Set place phone number
     *
     * @param string|null $phone
     * @return void
     */
    public function setPhone(?string $phone): void;

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
     * Place updated date
     *
     * @return string|null
     */
    public function getUpdatedAt(): ?string;

    /**
     * Set place updated date
     *
     * @param string|null $updatedAt
     * @return void
     */
    public function setUpdatedAt(?string $updatedAt): void;
}
