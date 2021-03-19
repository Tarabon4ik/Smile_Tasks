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

/**
 * Class Manifestation
 */
class Manifestation extends AbstractEntity
{
    /**
     * Getter and lazy loader for _type
     *
     * @return \Magento\Eav\Model\Entity\Type
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getEntityType()
    {
        if (empty($this->_type)) {
            $this->setType(\Smile\Manifestation\Model\Manifestation::ENTITY);
        }
        return parent::getEntityType();
    }
}

