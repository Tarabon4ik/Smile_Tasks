<?php
/**
 * Model Place
 *
 * @category  Smile
 * @package   Smile\Manifestation
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */
declare(strict_types=1);

namespace Smile\Manifestation\Model;

use Magento\Framework\Model\AbstractExtensibleModel;
use Smile\Manifestation\Api\Data\PlaceInterface;
use Smile\Manifestation\Model\ResourceModel\Place as PlaceResourceModel;

/**
 * {@inheritdoc}
 *
 * @codeCoverageIgnore
 */
class Place extends AbstractExtensibleModel implements PlaceInterface
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY = 'manifestation_place';

    /**
     * Init resource model and id field
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init(PlaceResourceModel::class);
        $this->setIdFieldName(PlaceInterface::PLACE_ID);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getData(self::PLACE_ID);
    }

    /**
     * @inheritdoc
     */
    public function setId($placeId): void
    {
        $this->setData(self::PLACE_ID, $placeId);
    }

    /**
     * @inheritdoc
     */
    public function getName(): ?string
    {
        return $this->getData(self::NAME);
    }

    /**
     * @inheritdoc
     */
    public function setName(?string $name): void
    {
        $this->setData(self::NAME, $name);
    }

    /**
     * @inheritdoc
     */
    public function getEmail(): ?string
    {
        return $this->getData(self::EMAIL);
    }

    /**
     * @inheritdoc
     */
    public function setEmail(?string $email): void
    {
        $this->setData(self::EMAIL, $email);
    }

    /**
     * @inheritdoc
     */
    public function getContactName(): ?string
    {
        return $this->getData(self::CONTACT_NAME);
    }

    /**
     * @inheritdoc
     */
    public function setContactName(?string $contactName): void
    {
        $this->setData(self::CONTACT_NAME, $contactName);
    }

    /**
     * @inheritdoc
     */
    public function getImage(): ?string
    {
        return $this->getData(self::IMAGE);
    }

    /**
     * @inheritdoc
     */
    public function setImage(?string $image): void
    {
        $this->setData(self::IMAGE, $image);
    }

    /**
     * @inheritdoc
     */
    public function isEnabled(): ?bool
    {
        return $this->getData(self::ENABLED) === null ?
            null :
            (bool)$this->getData(self::ENABLED);
    }

    /**
     * @inheritdoc
     */
    public function setEnabled(?bool $enabled): void
    {
        $this->setData(self::ENABLED, $enabled);
    }

    /**
     * @inheritdoc
     */
    public function getDescription(): ?string
    {
        return $this->getData(self::DESCRIPTION);
    }

    /**
     * @inheritdoc
     */
    public function setDescription(?string $description): void
    {
        $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * @inheritdoc
     */
    public function getLatitude(): ?float
    {
        return $this->getData(self::LATITUDE) === null ?
            null :
            (float)$this->getData(self::LATITUDE);
    }

    /**
     * @inheritdoc
     */
    public function setLatitude(?float $latitude): void
    {
        $this->setData(self::LATITUDE, $latitude);
    }

    /**
     * @inheritdoc
     */
    public function getLongitude(): ?float
    {
        return $this->getData(self::LONGITUDE) === null ?
            null :
            (float)$this->getData(self::LONGITUDE);
    }

    /**
     * @inheritdoc
     */
    public function setLongitude(?float $longitude): void
    {
        $this->setData(self::LONGITUDE, $longitude);
    }

    /**
     * @inheritdoc
     */
    public function getCountryId(): ?string
    {
        return $this->getData(self::COUNTRY_ID);
    }

    /**
     * @inheritdoc
     */
    public function setCountryId(?string $countryId): void
    {
        $this->setData(self::COUNTRY_ID, $countryId);
    }

    /**
     * @inheritdoc
     */
    public function getRegionId(): ?string
    {
        return $this->getData(self::REGION_ID) === null ?
            null :
            (string)$this->getData(self::REGION_ID);
    }

    /**
     * @inheritdoc
     */
    public function setRegionId(?string $regionId): void
    {
        $this->setData(self::REGION_ID, $regionId);
    }

    /**
     * @inheritdoc
     */
    public function getRegion(): ?string
    {
        return $this->getData(self::REGION);
    }

    /**
     * @inheritdoc
     */
    public function setRegion(?string $region): void
    {
        $this->setData(self::REGION, $region);
    }

    /**
     * @inheritdoc
     */
    public function getCity(): ?string
    {
        return $this->getData(self::CITY);
    }

    /**
     * @inheritdoc
     */
    public function setCity(?string $city): void
    {
        $this->setData(self::CITY, $city);
    }

    /**
     * @inheritdoc
     */
    public function getStreet(): ?string
    {
        return $this->getData(self::STREET);
    }

    /**
     * @inheritdoc
     */
    public function setStreet(?string $street): void
    {
        $this->setData(self::STREET, $street);
    }

    /**
     * @inheritdoc
     */
    public function getPhone(): ?string
    {
        return $this->getData(self::PHONE);
    }

    /**
     * @inheritdoc
     */
    public function setPhone(?string $phone): void
    {
        $this->setData(self::PHONE, $phone);
    }

    /**
     * @inheritdoc
     */
    public function getCreatedAt(): ?string
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @inheritdoc
     */
    public function setCreatedAt(?string $createdAt): void
    {
        $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * @inheritdoc
     */
    public function getUpdatedAt(): ?string
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * @inheritdoc
     */
    public function setUpdatedAt(?string $updatedAt): void
    {
        $this->setData(self::UPDATED_AT, $updatedAt);
    }

    /**
     * Retrieve place id by name
     *
     * @param string $name
     * @return int
     */
    public function getIdByName($name)
    {
        return $this->_getResource()->getIdByName($name);
    }
}
