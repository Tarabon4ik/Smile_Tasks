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

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Smile\Manifestation\Model\Manifestation;
use Smile\Manifestation\Model\ResourceModel\Manifestation as ManifestationResourceModel;

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
        $this->_init(Manifestation::class, ManifestationResourceModel::class);
    }
}
