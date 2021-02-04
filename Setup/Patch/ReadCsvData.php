<?php
/**
 * Read Csv Data
 *
* @category  Smile
* @package   Smile\Attribute
* @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
* @copyright 2021 Smile
*/

namespace Smile\Catalog\Setup\Patch;

use Magento\Framework\Setup\SampleData\Context as SampleDataContext;
use Magento\Framework\Setup\SampleData\FixtureManager;
use Magento\Framework\Exception\LocalizedException;

/**
* Class ReadCsvData
*/
class ReadCsvData
{
    /**#@+
    * Paths to .csv files
    */
    const EAV_ATTRIBUTE_SET = 'Smile_Attribute::fixtures/eav_attribute_set.csv';
    const EAV_ATTRIBUTE_GROUP = 'Smile_Attribute::fixtures/eav_attribute_group.csv';
    const EAV_ATTRIBUTES = 'Smile_Attribute::fixtures/eav_attribute.csv';
    const PRODUCT = 'Smile_Attribute::fixtures/product.csv';
    const TAX_RATE = 'Smile_Attribute::fixtures/tax_rate.csv';
    const TAX_RULE = 'Smile_Attribute::fixtures/tax_rule.csv';
    const TAX_CLASS = 'Smile_Attribute::fixtures/tax_class.csv';
    /**#@-*/

    /**
    * Fixture Manager
    *
    * @var FixtureManager
    */
    public $fixtureManager;

    /**
    * ReadCsvData constructor
    *
    * @param SampleDataContext $sampleDataContext
    */
    public function __construct(SampleDataContext $sampleDataContext)
    {
    $this->fixtureManager = $sampleDataContext->getFixtureManager();
    }

    /**
    * Read EavAttributeSet Csv
    *
    * @return string
    *
    * @throws LocalizedException
    */
    public function readEavAttributeSetCsv()
    {
    return $this->fixtureManager->getFixture(self::EAV_ATTRIBUTE_SET);
    }

    /**
    * Read EavAttributeGroup Csv
    *
    * @return string
    *
    * @throws LocalizedException
    */
    public function readEavAttributeGroupCsv()
    {
        return $this->fixtureManager->getFixture(self::EAV_ATTRIBUTE_GROUP);
    }

    /**
    * Read EavAttributes Csv
    *
    * @return string
    *
    * @throws LocalizedException
    */
    public function readEavAttributesCsv()
    {
        return $this->fixtureManager->getFixture(self::EAV_ATTRIBUTES);
    }

    /**
    * Read Product Csv
    *
    * @return string
    *
    * @throws LocalizedException
    */
    public function readProductCsv()
    {
        return $this->fixtureManager->getFixture(self::PRODUCT);
    }

    /**
    * Read Tax Rate Csv
    *
    * @return string
    *
    * @throws LocalizedException
    */
    public function readTaxRateCsv()
    {
        return $this->fixtureManager->getFixture(self::TAX_RATE);
    }

    /**
    * Read Tax Rule Csv
    *
    * @return string
    *
    * @throws LocalizedException
    */
    public function readTaxRuleCsv()
    {
        return $this->fixtureManager->getFixture(self::TAX_RULE);
    }

    /**
    * Read Tax Class Csv
    *
    * @return string
    *
    * @throws LocalizedException
    */
    public function readTaxClassCsv()
    {
        return $this->fixtureManager->getFixture(self::TAX_CLASS);
    }
}
