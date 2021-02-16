<?php
/**
 * Read Csv Data
 *
* @category  Smile
* @package   Smile\Catalog
* @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
* @copyright 2021 Smile
*/

namespace Smile\Catalog\Setup\Patch;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\SampleData\Context as SampleDataContext;
use Magento\Framework\Setup\SampleData\FixtureManager;

/**
* Class ReadCsvData
*/
class ReadCsvData
{
    /**#@+
    * Paths to .csv files
    */
    const PRODUCT = 'Smile_Catalog::fixtures/product.csv';
    const CATEGORY = 'Smile_Catalog::fixtures/category.csv';
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
     * Read Category Csv
     *
     * @return string
     *
     * @throws LocalizedException
     */
    public function readCategoryCsv()
    {
        return $this->fixtureManager->getFixture(self::CATEGORY);
    }
}
