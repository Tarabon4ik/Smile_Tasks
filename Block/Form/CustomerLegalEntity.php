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

use Smile\Checkout\Model\Customer\Attribute\LegalEntityOptions as LegalEntityAttributeOptions;
use Magento\Framework\View\Element\Template;

/**
 * Class CustomerLegalEntity
 */
class CustomerLegalEntity extends Template
{
    /**
     * @var LegalEntityAttributeOptions
     */
    protected $legalEntityAttributeOptions;

    /**
     * CustomerLegalEntity constructor
     *
     * @param Template\Context $context
     * @param LegalEntityAttributeOptions $legalEntityAttributeOptions
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        LegalEntityAttributeOptions $legalEntityAttributeOptions,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->legalEntityAttributeOptions = $legalEntityAttributeOptions;
    }

    /**
     * Get Juridical Person
     *
     * @return string
     */
    public function getJuridicalPerson()
    {
        return $this->legalEntityAttributeOptions->getOptionText('0');
    }

    /**
     * Get Natural Person
     *
     * @return string
     */
    public function getNaturalPerson()
    {
        return $this->legalEntityAttributeOptions->getOptionText('1');
    }
}
