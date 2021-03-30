<?php
/**
 * Collection ManifestationPlaceLink
 *
 * @category  Smile
 * @package   Smile\Manifestation
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */
declare(strict_types=1);

namespace Smile\Manifestation\Model\ResourceModel\ManifestationPlaceLink;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Smile\Manifestation\Model\ResourceModel\ManifestationPlaceLink as ManifestationPlaceLinkResourceModel;
use Smile\Manifestation\Model\ManifestationPlaceLink as ManifestationPlaceLinkModel;

/**
 * Resource Collection of StockSourceLink entities
 * It is not an API because StockSourceLink used in module internally
 */
class Collection extends AbstractCollection
{
    /**
     * Primary id field name
     */
    protected $_idFieldName = ManifestationPlaceLinkModel::LINK_ID;

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(ManifestationPlaceLinkModel::class, ManifestationPlaceLinkResourceModel::class);
    }
}
