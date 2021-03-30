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
use Magento\Eav\Model\Entity\Attribute\UniqueValidationInterface;
use Magento\Eav\Model\Entity\Context;
use Magento\Eav\Model\Entity\Type;
use Magento\Framework\Exception\LocalizedException;
use Smile\Manifestation\Api\Data\ManifestationInterface;

/**
 * Class Manifestation
 */
class Manifestation extends AbstractEntity
{
    /**
     * @param Context $context
     * @param array $data
     * @param UniqueValidationInterface|null $uniqueValidator
     */
    public function __construct(
        Context $context,
        $data = [],
        UniqueValidationInterface $uniqueValidator = null
    ) {
        parent::__construct($context, $data, $uniqueValidator);
    }

    /**
     * Retrieve Manifestation entity default attributes
     *
     * @return string[]
     */
    protected function _getDefaultAttributes()
    {
        return [
            ManifestationInterface::TITLE,
            ManifestationInterface::DESCRIPTION,
            ManifestationInterface::META_TITLE,
            ManifestationInterface::META_DESCRIPTION,
            ManifestationInterface::START_DATE,
            ManifestationInterface::END_DATE,
            ManifestationInterface::IS_NEED_WATER,
            ManifestationInterface::IS_NEED_ELECTRICITY,
            ManifestationInterface::IMAGE
        ];
    }

    /**
     * Getter and lazy loader for _type
     *
     * @return Type
     * @throws LocalizedException
     */
    public function getEntityType()
    {
        if (empty($this->_type)) {
            $this->setType(\Smile\Manifestation\Model\Manifestation::ENTITY);
        }
        return parent::getEntityType();
    }

    /**
     * Get product identifier by title
     *
     * @param  string $title
     * @return int|false
     */
    public function getIdByTitle($title)
    {
        $connection = $this->getConnection();

        $select = $connection->select()->from($this->getEntityTable(), 'entity_id')->where('title = :title');

        $bind = [':title' => (string)$title];

        return $connection->fetchOne($select, $bind);
    }
}

