<?php
/**
 * Model Place
 *
 * @category  Smile
 * @package   Smile\Manifestation
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */

namespace Smile\Manifestation\Model;

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Smile\Manifestation\Api\Data\PlaceInterface;
use Smile\Manifestation\Model\ResourceModel\Place as PlaceResourceModel;

/**
 * Class Place
 */
class Place extends AbstractModel implements PlaceInterface
{
    /**
     * Place Constructor
     *
     * @param Context $context
     * @param Registry $registry
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Init resource model and id field
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init(PlaceResourceModel::class);
        $this->setIdFieldName(PlaceInterface::ID);
    }

    /**
     * Get Id
     *
     * @return int
     */
    public function getId()
    {
        return $this->getData(PlaceInterface::ID);
    }

    /**
     * Set id
     *
     * @param int $id
     *
     * @return PlaceInterface
     */
    public function setId($id): PlaceInterface
    {
        return $this->setData(PlaceInterface::ID, $id);
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getData(PlaceInterface::NAME);
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return PlaceInterface
     */
    public function setName($name): PlaceInterface
    {
        return $this->setData(PlaceInterface::NAME, $name);
    }

    /**
     * Get description
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->getData(PlaceInterface::DESCRIPTION);
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return PlaceInterface
     */
    public function setDescription($description): PlaceInterface
    {
        return $this->setData(PlaceInterface::DESCRIPTION, $description);
    }

    /**
     * Get Location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->getData(PlaceInterface::LOCATION);
    }

    /**
     * Set Location
     *
     * @param string $location
     *
     * @return PlaceInterface
     */
    public function setLocation($location): PlaceInterface
    {
        return $this->setData(PlaceInterface::LOCATION, $location);
    }

    /**
     * Get Longitude
     *
     * @return string|null
     */
    public function getLongitude()
    {
        return $this->getData(PlaceInterface::LONGITUDE);
    }

    /**
     * Set Longitude
     *
     * @param string $longitude
     *
     * @return PlaceInterface
     */
    public function setLongitude($longitude): PlaceInterface
    {
        return $this->setData(PlaceInterface::LONGITUDE, $longitude);
    }

    /**
     * Get Latitude
     *
     * @return string|null
     */
    public function getLatitude()
    {
        return $this->getData(PlaceInterface::LATITUDE);
    }

    /**
     * Set Latitude
     *
     * @param string $latitude
     *
     * @return PlaceInterface
     */
    public function setLatitude($latitude): PlaceInterface
    {
        return $this->setData(PlaceInterface::LATITUDE, $latitude);
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->getData(PlaceInterface::IMAGE);
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return PlaceInterface
     */
    public function setImage($image): PlaceInterface
    {
        return $this->setData(PlaceInterface::IMAGE, $image);
    }

    /**
     * Retrieve assigned manifestation Ids
     *
     * @return array
     */
    public function getManifestationIds()
    {
        return $this->getData(PlaceInterface::MANIFESTATION_IDS);
    }

    /**
     * Set place manifestations
     *
     * @param array $manifestationIds
     *
     * @return PlaceInterface
     */
    public function setManifestationIds($manifestationIds)
    {
        return $this->setData(PlaceInterface::MANIFESTATION_IDS, $manifestationIds);
    }

    /**
     * Get created at
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->getData(PlaceInterface::CREATED_AT);
    }


    /**
     * Set created at
     *
     * @param string $createdAt
     *
     * @return PlaceInterface
     */
    public function setCreatedAt($createdAt): PlaceInterface
    {
        return $this->setData(PlaceInterface::CREATED_AT, $createdAt);
    }

    /**
     * Get updated at
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->getData(PlaceInterface::UPDATED_AT);
    }

    /**
     * Set updated at
     *
     * @param string $updatedAt
     *
     * @return PlaceInterface
     */
    public function setUpdatedAt($updatedAt): PlaceInterface
    {
        return $this->setData(PlaceInterface::UPDATED_AT, $updatedAt);
    }
}
