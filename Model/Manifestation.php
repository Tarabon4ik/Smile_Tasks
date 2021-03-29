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

use Magento\Framework\Api\AttributeValueFactory;
use Smile\Manifestation\Api\Data\ManifestationInterface;
use Smile\Manifestation\Model\ResourceModel\Manifestation as ManifestationResourceModel;

/**
 * Class Manifestation
 */
class Manifestation extends AbstractModel implements ManifestationInterface
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY = 'manifestation';

    /**
     * Product Store Id
     */
    const STORE_ID = 'store_id';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory
     * @param AttributeValueFactory $customAttributeFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Smile\Manifestation\Model\ResourceModel\Manifestation $resource
     * @param \Smile\Manifestation\Model\ResourceModel\Manifestation\Collection $resourceCollection
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $customAttributeFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Smile\Manifestation\Model\ResourceModel\Manifestation $resource,
        \Smile\Manifestation\Model\ResourceModel\Manifestation\Collection $resourceCollection,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $storeManager,
            $resource,
            $resourceCollection,
            $data
        );
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
        $this->setIdFieldName(self::MANIFESTATION_ID);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getData(self::MANIFESTATION_ID) === null ?
            null :
            (int)$this->getData(self::MANIFESTATION_ID);
    }

    /**
     * @inheritdoc
     */
    public function setId($id): void
    {
        $this->setData(self::MANIFESTATION_ID, $id);
    }

    /**
     * @inheritdoc
     */
    public function getTitle(): ?string
    {
        return $this->getData(self::TITLE);
    }

    /**
     * @inheritdoc
     */
    public function setTitle(?string $title): void
    {
        $this->setData(self::TITLE, $title);
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
    public function getMetaTitle(): ?string
    {
        return $this->getData(self::META_TITLE);
    }

    /**
     * @inheritdoc
     */
    public function setMetaTitle(?string $metaTitle): void
    {
        $this->setData(self::META_TITLE, $metaTitle);
    }

    /**
     * @inheritdoc
     */
    public function getMetaDescription(): ?string
    {
        return $this->getData(self::META_DESCRIPTION);
    }

    /**
     * @inheritdoc
     */
    public function setMetaDescription(?string $metaDescription): void
    {
        $this->setData(self::META_DESCRIPTION, $metaDescription);
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
     * @inheritdoc
     */
    public function getStartDate(): ?string
    {
        return $this->getData(self::START_DATE);
    }

    /**
     * @inheritdoc
     */
    public function setStartDate(?string $startDate): void
    {
        $this->setData(self::START_DATE, $startDate);
    }

    /**
     * @inheritdoc
     */
    public function getEndDate(): ?string
    {
        return $this->getData(self::END_DATE);
    }

    /**
     * @inheritdoc
     */
    public function setEndDate(?string $endDate): void
    {
        $this->setData(self::END_DATE, $endDate);
    }

    /**
     * @inheritdoc
     */
    public function getIsNeedElectricity(): ?bool
    {
        return $this->getData(self::IS_NEED_ELECTRICITY);
    }

    /**
     * @inheritdoc
     */
    public function setIsNeedElectricity(?bool $isNeedElectricity): void
    {
        $this->setData(self::IS_NEED_ELECTRICITY, $isNeedElectricity);
    }

    /**
     * @inheritdoc
     */
    public function getIsNeedWater(): ?bool
    {
        return $this->getData(self::IS_NEED_WATER);
    }

    /**
     * @inheritdoc
     */
    public function setIsNeedWater(?bool $isNeedWater): void
    {
        $this->setData(self::IS_NEED_WATER, $isNeedWater);
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
     * Retrieve Store Id
     *
     * @return int
     */
    public function getStoreId()
    {
        if ($this->hasData(self::STORE_ID)) {
            return (int)$this->getData(self::STORE_ID);
        }
        return (int)$this->_storeManager->getStore()->getId();
    }

    /**
     * Set product store id
     *
     * @param int $storeId
     * @return $this
     */
    public function setStoreId($storeId)
    {
        return $this->setData(self::STORE_ID, $storeId);
    }

    /**
     * Retrieve product id by sku
     *
     * @param string $title
     * @return int
     */
    public function getIdByTitle($title)
    {
        return $this->_getResource()->getIdByTitle($title);
    }
}
