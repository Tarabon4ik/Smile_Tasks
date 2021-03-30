<?php
/**
 * Place Helper
 *
 * @category  Smile
 * @package   Smile\Manifestation
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */
namespace Smile\Manifestation\Model\ResourceModel;

use Magento\Framework\App\ResourceConnection;
use Magento\Eav\Model\ResourceModel\Helper as EavHelper;

/**
 * Eav Mysql resource helper model
 */
class Helper extends EavHelper
{
    /**
     * @param ResourceConnection $resource
     * @param string $modulePrefix
     */
    public function __construct(ResourceConnection $resource, $modulePrefix = 'Smile_Manifestation')
    {
        parent::__construct($resource, $modulePrefix);
    }
}
