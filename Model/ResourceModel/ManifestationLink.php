<?php
/**
 * ResourceModel ManifestationLink
 *
 * @category  Smile
 * @package   Smile\Manifestation
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */

namespace Smile\Manifestation\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Smile\Manifestation\Api\Data\ManifestationLinkInterface;

/**
 * Class ManifestationLink
 */
class ManifestationLink extends AbstractDb
{
    /**
     * Initialize main table and table id field
     *
     * @return void
     * @codeCoverageIgnore
     */
    protected function _construct()
    {
        $this->_init('eav_manifestation_link', ManifestationLinkInterface::ID);
    }
}
