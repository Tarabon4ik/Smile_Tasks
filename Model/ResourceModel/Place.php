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
use Magento\Eav\Model\Entity\Type;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Place
 */
class Place extends AbstractEntity
{
    /**
     * Getter and lazy loader for _type
     *
     * @return Type
     *
     * @throws LocalizedException
     */
    public function getEntityType()
    {
        if (empty($this->_type)) {
            $this->setType(\Smile\Manifestation\Model\Place::ENTITY);
        }
        return parent::getEntityType();
    }

    /**
     * Get place identifier by name
     *
     * @param  string $name
     * @return int|false
     */
    public function getIdByName($name)
    {
        $connection = $this->getConnection();

        $select = $connection->select()->from($this->getEntityTable(), 'entity_id')->where('name = :name');

        $bind = [':name' => (string)$name];

        return $connection->fetchOne($select, $bind);
    }
}
