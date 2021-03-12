<?php
/**
 * Model Manifestation
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
use Smile\Manifestation\Api\Data\ManifestationInterface;
use Smile\Manifestation\Model\ResourceModel\Manifestation as ManifestationResourceModel;

/**
 * Class Manifestation
 */
class Manifestation extends AbstractModel implements ManifestationInterface
{
    /**
     * Manifestation Constructor
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
        $this->_init(ManifestationResourceModel::class);
        $this->setIdFieldName(ManifestationInterface::ID);
    }

    /**
     * Get Id
     *
     * @return int
     */
    public function getId()
    {
        return $this->getData(ManifestationInterface::ID);
    }

    /**
     * Set id
     *
     * @param int $id
     *
     * @return ManifestationInterface
     */
    public function setId($id): ManifestationInterface
    {
        return $this->setData(ManifestationInterface::ID, $id);
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getData(ManifestationInterface::TITLE);
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return ManifestationInterface
     */
    public function setTitle($title): ManifestationInterface
    {
        return $this->setData(ManifestationInterface::TITLE, $title);
    }

    /**
     * Get description
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->getData(ManifestationInterface::DESCRIPTION);
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return ManifestationInterface
     */
    public function setDescription($description): ManifestationInterface
    {
        return $this->setData(ManifestationInterface::DESCRIPTION, $description);
    }

    /**
     * Get Meta Title
     *
     * @return string|null
     */
    public function getMetaTitle()
    {
        return $this->getData(ManifestationInterface::META_TITLE);
    }

    /**
     * Set Meta Title
     *
     * @param string $metaTitle
     *
     * @return ManifestationInterface
     */
    public function setMetaTitle($metaTitle): ManifestationInterface
    {
        return $this->setData(ManifestationInterface::META_TITLE, $metaTitle);
    }

    /**
     * Get Meta Description
     *
     * @return string|null
     */
    public function getMetaDescription()
    {
        return $this->getData(ManifestationInterface::META_DESCRIPTION);
    }

    /**
     * Set Meta Description
     *
     * @param string $metaDescription
     *
     * @return ManifestationInterface
     */
    public function setMetaDescription($metaDescription): ManifestationInterface
    {
        return $this->setData(ManifestationInterface::META_DESCRIPTION, $metaDescription);
    }

    /**
     * Get created at
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->getData(ManifestationInterface::CREATED_AT);
    }


    /**
     * Set created at
     *
     * @param string $createdAt
     *
     * @return ManifestationInterface
     */
    public function setCreatedAt($createdAt): ManifestationInterface
    {
        return $this->setData(ManifestationInterface::CREATED_AT, $createdAt);
    }

    /**
     * Get updated at
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->getData(ManifestationInterface::UPDATED_AT);
    }

    /**
     * Set updated at
     *
     * @param string $updatedAt
     *
     * @return ManifestationInterface
     */
    public function setUpdatedAt($updatedAt): ManifestationInterface
    {
        return $this->setData(ManifestationInterface::UPDATED_AT, $updatedAt);
    }

    /**
     * Get start date
     *
     * @return string
     */
    public function getStartDate()
    {
        return $this->getData(ManifestationInterface::START_DATE);
    }

    /**
     * Set start date
     *
     * @param string $startDate
     *
     * @return ManifestationInterface
     */
    public function setStartDate($startDate): ManifestationInterface
    {
        return $this->setData(ManifestationInterface::START_DATE, $startDate);
    }

    /**
     * Get end date
     *
     * @return string
     */
    public function getEndDate()
    {
        return $this->getData(ManifestationInterface::END_DATE);
    }

    /**
     * Set end date
     *
     * @param string $endDate
     *
     * @return ManifestationInterface
     */
    public function setEndDate($endDate): ManifestationInterface
    {
        return $this->setData(ManifestationInterface::END_DATE, $endDate);
    }

    /**
     * Check whether manifestation is need electricity
     *
     * @return bool
     */
    public function getIsNeedElectricity()
    {
        return $this->getData(ManifestationInterface::IS_NEED_ELECTRICITY);
    }

    /**
     * Set whether manifestation is need electricity
     *
     * @param bool $isNeedElectricity
     *
     * @return ManifestationInterface
     */
    public function setIsNeedElectricity($isNeedElectricity): ManifestationInterface
    {
        return $this->setData(ManifestationInterface::IS_NEED_ELECTRICITY, $isNeedElectricity);
    }

    /**
     * Check whether manifestation is need water
     *
     * @return bool
     */
    public function getIsNeedWater()
    {
        return $this->getData(ManifestationInterface::IS_NEED_WATER);
    }

    /**
     * Set whether manifestation is need water
     *
     * @param bool $isNeedWater
     *
     * @return ManifestationInterface
     */
    public function setIsNeedWater($isNeedWater): ManifestationInterface
    {
        return $this->setData(ManifestationInterface::IS_NEED_WATER, $isNeedWater);
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->getData(ManifestationInterface::IMAGE);
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return ManifestationInterface
     */
    public function setImage($image): ManifestationInterface
    {
        return $this->setData(ManifestationInterface::IMAGE, $image);
    }

    /**
     * Retrieve assigned place Ids
     *
     * @return array
     */
    public function getPlaceIds()
    {
        return $this->getData(ManifestationInterface::PLACE_IDS);
    }

    /**
     * Set manifestation place
     *
     * @param array $placeIds
     *
     * @return ManifestationInterface
     */
    public function setPlaceIds($placeIds)
    {
        return $this->setData(ManifestationInterface::PLACE_IDS, $placeIds);
    }
}
