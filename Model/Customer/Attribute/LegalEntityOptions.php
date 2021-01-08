<?php
/**
 * Legal Entity Options
 *
 * @category  Smile
 * @package   Smile\Checkout
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */

namespace Smile\Checkout\Model\Customer\Attribute;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Eav\Model\Entity\Attribute\Source\SourceInterface;
use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class LegalEntityOptions
 */
class LegalEntityOptions extends AbstractSource implements SourceInterface, OptionSourceInterface
{
    /**#@+
     * Legal Entity Attribute values
     */
    const JURIDICAL_PERSON = 1;
    const NATURAL_PERSON = 0;
    /**#@-*/

    /**
     * Retrieve Juridical Person Id
     *
     * @return int[]
     */
    public function getJuridicalPerson()
    {
        return [self::JURIDICAL_PERSON];
    }

    /**
     * Retrieve Natural Person Id
     *
     * @return int[]
     */
    public function getNaturalPerson()
    {
        return [self::NATURAL_PERSON];
    }

    /**
     * Retrieve option array
     *
     * @return string[]
     */
    public static function getOptionArray()
    {
        return [self::JURIDICAL_PERSON => __('Juridical Person'), self::NATURAL_PERSON => __('Natural Person')];
    }

    /**
     * Retrieve option array with empty value
     *
     * @return string[]
     */
    public function getAllOptions()
    {
        $result = [];

        foreach (self::getOptionArray() as $index => $value) {
            $result[] = ['value' => $index, 'label' => $value];
        }

        return $result;
    }

    /**
     * Retrieve option text by option value
     *
     * @param string $optionId
     *
     * @return string
     */
    public function getOptionText($optionId)
    {
        $options = self::getOptionArray();

        return isset($options[$optionId]) ? $options[$optionId] : null;
    }
}
