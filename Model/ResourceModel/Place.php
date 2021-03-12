<?php
/**
 * ResourceModel Manifestation
 *
 * @category  Smile
 * @package   Smile\Manifestation
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */

namespace Smile\Manifestation\Model\ResourceModel;

use Magento\Eav\Model\Entity\AbstractEntity;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Manifestation
 */
class Place extends AbstractEntity
{
    /**
     * Get Entity Type
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return \Magento\Eav\Model\Entity\Type
     */
    public function getEntityType()
    {
        if (empty($this->_type)) {
            $this->setType(\Smile\Manifestation\Model\Place::ENTITY);
        }
        return parent::getEntityType();
    }
}
