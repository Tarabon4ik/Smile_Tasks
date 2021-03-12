<?php

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
    public function getPlaceId(): ?string
    {
        return $this->getData(self::PLACE_ID);
    }

    /**
     * @inheritdoc
     */
    public function setPlaceId(?int $placeId): void
    {
        $this->setData(self::PLACE_ID, $placeId);
    }

    /**
     * @inheritdoc
     */
    public function getManifestationId(): ?int
    {
        return $this->getData(self::MANIFESTATION_ID) === null ?
            null :
            (int)$this->getData(self::MANIFESTATION_ID);
    }

    /**
     * @inheritdoc
     */
    public function setManifestationId(?int $manifestationId): void
    {
        $this->setData(self::MANIFESTATION_ID, $manifestationId);
    }

    /**
     * @inheritdoc
     */
    public function getPriority(): ?int
    {
        return $this->getData(self::PRIORITY) === null ?
            null :
            (int)$this->getData(self::PRIORITY);
    }

    /**
     * @inheritdoc
     */
    public function setPriority(?int $priority): void
    {
        $this->setData(self::PRIORITY, $priority);
    }
}
