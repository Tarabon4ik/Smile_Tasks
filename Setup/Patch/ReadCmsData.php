<?php
/**
 * ReadCSV CMS Page Data
 *
 * @category  Smile
 * @package   Smile\Contract
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */

namespace Smile\Contract\Setup\Patch;

use Magento\Framework\Setup\SampleData\Context as SampleDataContext;
use Magento\Framework\Setup\SampleData\FixtureManager;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class ReadCmsPageData
 */
class ReadCmsData
{
    /**#@+
     * Paths to .csv files
     */
    const CMS_PAGE = 'Smile_Contract::fixtures/cms_page.csv';
    const CMS_BLOCK = 'Smile_Contract::fixtures/cms_block.csv';
    /**#@-*/

    /**
     * Fixture Manager
     *
     * @var FixtureManager
     */
    public $fixtureManager;

    /**
     * ReadCmsPageData constructor
     *
     * @param SampleDataContext $sampleDataContext
     */
    public function __construct(SampleDataContext $sampleDataContext)
    {
        $this->fixtureManager = $sampleDataContext->getFixtureManager();
    }

    /**
     * Read CmsPage Csv
     *
     * @return string
     *
     * @throws LocalizedException
     */
    public function readCmsPageCsv()
    {
        return $this->fixtureManager->getFixture(self::CMS_PAGE);
    }

    /**
     * Read CmsPage Csv
     *
     * @return string
     *
     * @throws LocalizedException
     */
    public function readCmsBlockCsv()
    {
        return $this->fixtureManager->getFixture(self::CMS_BLOCK);
    }
}
