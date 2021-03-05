<?php
/**
 * Model ManifestationLink
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
use Smile\Manifestation\Api\Data\ManifestationLinkInterface;
use Smile\Manifestation\Model\ResourceModel\ManifestationLink as ManifestationLinkResourceModel;

/**
 * Class ManifestationLink
 */
class ManifestationLink extends AbstractModel implements ManifestationLinkInterface
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
        $this->_init(ManifestationLinkResourceModel::class);
        $this->setIdFieldName(ManifestationLinkInterface::ID);
    }

    /**
     * Get Id
     *
     * @return int
     */
    public function getId()
    {
        return $this->getData(ManifestationLinkInterface::ID);
    }

    /**
     * Set id
     *
     * @param int $id
     *
     * @return ManifestationLinkInterface
     */
    public function setId($id): ManifestationLinkInterface
    {
        return $this->setData(ManifestationLinkInterface::ID, $id);
    }

    /**
     * Get manifestationId
     *
     * @return int
     */
    public function getManifestationId()
    {
        return $this->getData(ManifestationLinkInterface::MANIFESTATION_ID);
    }

    /**
     * Set manifestationId
     *
     * @param int $manifestationId
     *
     * @return ManifestationLinkInterface
     */
    public function setManifestationId($manifestationId): ManifestationLinkInterface
    {
        return $this->setData(ManifestationLinkInterface::MANIFESTATION_ID, $manifestationId);
    }

    /**
     * Get placeId
     *
     * @return int
     */
    public function getPlaceId()
    {
        return $this->getData(ManifestationLinkInterface::PLACE_ID);
    }

    /**
     * Set placeId
     *
     * @param int $placeId
     *
     * @return ManifestationLinkInterface
     */
    public function setPlaceId($placeId): ManifestationLinkInterface
    {
        return $this->setData(ManifestationLinkInterface::PLACE_ID, $placeId);
    }
}
