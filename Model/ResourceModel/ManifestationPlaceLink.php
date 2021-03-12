<?php

declare(strict_types=1);

namespace Smile\Manifestation\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Implementation of basic operations for ManifestationPlaceLink entity for specific db layer
 */
class ManifestationPlaceLink extends AbstractDb
{
    /**#@+
     * Constants related to specific db layer
     */
    const TABLE_NAME = 'manifestation_place_link';
    const ID_FIELD_NAME = 'link_id';
    /**#@-*/

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, self::ID_FIELD_NAME);
    }
}
