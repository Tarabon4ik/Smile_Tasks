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

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Smile\Manifestation\Model\Place;
use Smile\Manifestation\Model\ResourceModel\Place as PlaceResourceModel;

/**
 * Class Collection
 */
class Collection extends AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected $_idFieldName = 'id';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(Place::class, PlaceResourceModel::class);
    }
}
