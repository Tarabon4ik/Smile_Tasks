<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Smile\Manifestation\Model\ResourceModel;

/**
 * Eav Mysql resource helper model
 */
class Helper extends \Magento\Eav\Model\ResourceModel\Helper
{
    /**
     * @param \Magento\Framework\App\ResourceConnection $resource
     * @param string $modulePrefix
     */
    public function __construct(\Magento\Framework\App\ResourceConnection $resource, $modulePrefix = 'Smile_Manifestation')
    {
        parent::__construct($resource, $modulePrefix);
    }
}
