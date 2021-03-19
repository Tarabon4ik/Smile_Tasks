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

use Smile\Manifestation\Model\Manifestation;
use Smile\Manifestation\Model\ResourceModel\Manifestation as ManifestationResourceModel;
use Magento\Eav\Model\Entity\Collection\AbstractCollection;

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
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(Manifestation::class, ManifestationResourceModel::class);
    }
}
