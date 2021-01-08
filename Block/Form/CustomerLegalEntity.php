<?php
/**
 * Block CustomerLegalEntity
 *
 * @category  Smile
 * @package   Smile\Checkout
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */

namespace Smile\Checkout\Block\Form;

use Magento\Framework\View\Element\Template;
use Magento\Eav\Model\Entity\Attribute;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Collection;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\Collection as OptionCollection;
use Magento\Eav\Model\Config;

/**
 * Class CustomerLegalEntity
 */
class CustomerLegalEntity extends Template
{
    /**
     * @var Config
     */
    protected $eavConfig;

    /**
     * @var Attribute
     */
    protected $_entityAttribute;

    /**
     * @var Collection
     */
    protected $_entityAttributeCollection;

    /**
     * @var OptionCollection
     */
    protected $_entityAttributeOptionCollection;

    /**
     * CustomerLegalEntity constructor
     *
     * @param Template\Context $context
     * @param Attribute $_entityAttribute
     * @param Collection $_entityAttributeCollection
     * @param OptionCollection $_entityAttributeOptionCollection
     * @param Config $eavConfig
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Config $eavConfig,
        Attribute $entityAttribute,
        Collection $entityAttributeCollection,
        OptionCollection $entityAttributeOptionCollection,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->eavConfig = $eavConfig;
        $this->_entityAttribute = $entityAttribute;
        $this->_entityAttributeCollection = $entityAttributeCollection;
        $this->_entityAttributeOptionCollection = $entityAttributeOptionCollection;
    }

    /**
     * Get all Legal Entity Options
     *
     * @return array
     */
    public function getLegalEntityOptions()
    {
        $attributeCode = 'legal_entity_type';
        $attribute = $this->eavConfig->getAttribute('customer', $attributeCode);
        $options = $attribute->getSource()->getAllOptions();
        $arr = [];
        foreach ($options as $option) {
            if ($option['value'] > 0) {
                $arr[] = $option;
            }
        }

        return $arr;
    }
}
