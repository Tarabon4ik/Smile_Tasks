<?php
/**
 * Collection ManifestationLink
 *
 * @category  Smile
 * @package   Smile\Manifestation
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */

namespace Smile\Manifestation\Model\ResourceModel\ManifestationLink;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Smile\Manifestation\Model\ManifestationLink;
use Smile\Manifestation\Model\ResourceModel\ManifestationLink as ManifestationLinkResourceModel;

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
        $this->_init(ManifestationLink::class, ManifestationLinkResourceModel::class);
    }
}
