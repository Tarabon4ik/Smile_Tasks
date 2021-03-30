<?php
/**
 * Model ManifestationPlaceLink
 *
 * @category  Smile
 * @package   Smile\Manifestation
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */
declare(strict_types=1);

namespace Smile\Manifestation\Model;

use Magento\Framework\Model\AbstractExtensibleModel;
use Smile\Manifestation\Api\Data\ManifestationPlaceLinkInterface;
use Smile\Manifestation\Model\ResourceModel\ManifestationPlaceLink as ManifestationPlaceLinkResourceModel;

/**
 * {@inheritdoc}
 *
 * @codeCoverageIgnore
 */
class ManifestationPlaceLink extends AbstractExtensibleModel implements ManifestationPlaceLinkInterface
{
    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(ManifestationPlaceLinkResourceModel::class);
    }

    /**
     * @inheritdoc
     */
    public function getLinkId()
    {
        return $this->getData(self::LINK_ID);
    }

    /**
     * @inheritdoc
     */
    public function setLinkId($linkId)
    {
        return $this->setData(self::LINK_ID, $linkId);
    }

    /**
     * @inheritdoc
     */
    public function getPlaceId()
    {
        return $this->getData(self::PLACE_ID);
    }

    /**
     * @inheritdoc
     */
    public function setPlaceId($placeId)
    {
        return $this->setData(self::PLACE_ID, $placeId);
    }

    /**
     * @inheritdoc
     */
    public function getManifestationId()
    {
        return $this->getData(self::MANIFESTATION_ID);
    }

    /**
     * @inheritdoc
     */
    public function setManifestationId($manifestationId)
    {
        return $this->setData(self::MANIFESTATION_ID, $manifestationId);
    }

    /**
     * @inheritdoc
     */
    public function getPriority()
    {
        return $this->getData(self::PRIORITY) === null ?
            null :
            (int)$this->getData(self::PRIORITY);
    }

    /**
     * @inheritdoc
     */
    public function setPriority($priority)
    {
        return $this->setData(self::PRIORITY, $priority);
    }
}
