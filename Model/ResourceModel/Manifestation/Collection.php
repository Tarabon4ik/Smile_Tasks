<?php
/**
 * Collection Manifestation
 *
 * @category  Smile
 * @package   Smile\Manifestation
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */

namespace Smile\Manifestation\Model\ResourceModel\Manifestation;

use Magento\Eav\Model\Config;
use Magento\Eav\Model\Entity\AbstractEntity;
use Magento\Framework\App\Config\Element;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactory;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Validator\UniversalFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Smile\Manifestation\Model\Manifestation;
use Smile\Manifestation\Model\ResourceModel\Collection\AbstractCollection;
use Smile\Manifestation\Model\ResourceModel\Helper as ManifestationHelper;
use Smile\Manifestation\Model\ResourceModel\Manifestation as ManifestationResourceModel;

/**
 * Class Collection
 */
class Collection extends AbstractCollection
{
    /**
     * Primary id field name
     */
    protected $_idFieldName = Manifestation::MANIFESTATION_ID;

    /**
     * Collection constructor
     *
     * @param EntityFactory $entityFactory
     * @param LoggerInterface $logger
     * @param FetchStrategyInterface $fetchStrategy
     * @param ManagerInterface $eventManager
     * @param Config $eavConfig
     * @param ResourceConnection $resource
     * @param \Magento\Eav\Model\EntityFactory $eavEntityFactory
     * @param ManifestationHelper $resourceHelper
     * @param UniversalFactory $universalFactory
     * @param StoreManagerInterface $storeManager
     * @param AdapterInterface|null $connection
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        EntityFactory $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        Config $eavConfig,
        ResourceConnection $resource,
        \Magento\Eav\Model\EntityFactory $eavEntityFactory,
        ManifestationHelper $resourceHelper,
        UniversalFactory $universalFactory,
        StoreManagerInterface $storeManager,
        AdapterInterface $connection = null
    ) {
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $eavConfig,
            $resource,
            $eavEntityFactory,
            $resourceHelper,
            $universalFactory,
            $storeManager,
            $connection
        );
    }

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(Manifestation::class, ManifestationResourceModel::class);
    }

    /**
     * Standard resource collection initialization. Needed for child classes.
     *
     * @param string $model
     * @param string $entityModel
     * @return $this
     */
    protected function _init($model, $entityModel)
    {
        return parent::_init($model, $entityModel);
    }

    /**
     * Set entity to use for attributes
     *
     * @param AbstractEntity $entity
     * @return $this
     */
    public function setEntity($entity)
    {
        return parent::setEntity($entity);
    }

    /**
     * Load attributes into loaded entities
     *
     * @param bool $printQuery
     * @param bool $logQuery
     * @return $this
     */
    public function _loadAttributes($printQuery = false, $logQuery = false)
    {
        return parent::_loadAttributes($printQuery, $logQuery);
    }

    /**
     * Add attribute to entities in collection. If $attribute=='*' select all attributes.
     *
     * @param array|string|integer|Element $attribute
     * @param bool|string $joinType
     * @return $this
     */
    public function addAttributeToSelect($attribute, $joinType = false)
    {
        return parent::addAttributeToSelect($attribute, $joinType);
    }
}
