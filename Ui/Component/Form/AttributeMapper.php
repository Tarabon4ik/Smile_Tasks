<?php

namespace Smile\Onepage\Ui\Component\Form;

use Magento\Eav\Api\Data\AttributeInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * @api
 */
class AttributeMapper
{
    /**
     * Form element mapping
     *
     * @var array
     */
    private $formElementMap = [
        'text' => 'input',
        'hidden' => 'input',
        'boolean' => 'checkbox',
    ];

    /**
     * EAV attribute properties to fetch from meta storage
     * @var array
     */
    private $metaPropertiesMap = [
        'dataType' => 'getFrontendInput',
        'visible' => 'getIsVisible',
        'required' => 'getIsRequired',
        'label' => 'getStoreLabel',
        'sortOrder' => 'getSortOrder',
        'notice' => 'getNote',
        'default' => 'getDefaultValue',
        'size' => 'getMultilineCount'
    ];

    /**
     * @var array
     */
    protected $validationRules = [
        'input_validation' => [
            'email' => ['validate-email' => true],
            'date' => ['validate-date' => true],
        ],
    ];

    /**
     * Get attributes meta
     *
     * @param AttributeInterface $attribute
     * @return array
     * @throws LocalizedException
     */
    public function map($attribute)
    {
        foreach ($this->metaPropertiesMap as $metaName => $methodName) {
            $value = $attribute->$methodName();
            $meta[$metaName] = $value;
            if ('getFrontendInput' === $methodName) {
                $meta['formElement'] = isset($this->formElementMap[$value])
                    ? $this->formElementMap[$value]
                    : $value;
            }
        }

        $rules = [];
        if (isset($meta['required']) && $meta['required'] == 1) {
            $rules['required-entry'] = true;
        }

        if ($attribute->getValidationRules()) {
            foreach ($attribute->getValidateRules() as $name => $value) {
                if (isset($this->validationRules[$name][$value])) {
                    $rules = array_merge($rules, $this->validationRules[$name][$value]);
                } else {
                    $rules[$name] = $value;
                }
            }
        }

        $meta['validation'] = $rules;

        return $meta;
    }
}
