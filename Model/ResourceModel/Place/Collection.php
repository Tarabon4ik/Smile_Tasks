<?php
/**
 * Collection Manifestation
 *
 * @category  Smile
 * @package   Smile\Manifestation
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */

namespace Smile\Manifestation\Model\ResourceModel\Place;

use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Smile\Manifestation\Model\Place;
use Smile\Manifestation\Model\ResourceModel\Place as PlaceResourceModel;

/**
 * Class Collection
 */
class Collection extends AbstractCollection
{
    /**
     * Primary id field name
     */
    protected $_idFieldName = Place::PLACE_ID;

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(Place::class, PlaceResourceModel::class);
    }
}
