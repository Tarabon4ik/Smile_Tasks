<?php
/**
 * SetupPatch Tax Rate Data
 *
 * @category  Smile
 * @package   Smile\Attribute
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */

namespace Smile\Attribute\Setup\Patch\Data;

use Magento\Eav\Setup\EavSetup;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\File\Csv;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\SampleData\Context as SampleDataContext;
use Magento\Tax\Api\TaxRateRepositoryInterface;
use Magento\Tax\Model\Calculation\RateFactory as TaxRateFactory;
use Smile\Attribute\Setup\Patch\ReadCsvData;

/**
 * Class TaxRateData
 */
class InstallTaxRate implements DataPatchInterface
{
    /**#@+
     * Tax Rate Columns
     */
    const CODE = 'code';
    const TAX_COUNTRY_ID = 'tax_country_id';
    const TAX_REGION_ID = 'tax_region_id';
    const TAX_POSTCODE = 'tax_postcode';
    const RATE = 'rate';
    const ZIP_IS_RANGE = 'zip_is_range';
    /**#@-*/

    /**
     * Eav Setup
     *
     * @var EavSetup
     */
    protected $eavSetup;

    /**
     * Csv reader
     *
     * @var Csv
     */
    protected $csvReader;

    /**
     * Read CsvData
     *
     * @var ReadCsvData
     */
    protected $readCsvData;

    /**
     * Module Data Setup
     *
     * @var ModuleDataSetupInterface
     */
    protected $moduleDataSetup;

    /**
     * Search Criteria Builder Factory
     *
     * @var SearchCriteriaBuilderFactory
     */
    protected $criteriaBuilderFactory;

    /**
     * Tax Rate Repository Interface
     *
     * @var TaxRateRepositoryInterface
     */
    protected $taxRateRepository;

    /**
     * Tax Rate Factory
     *
     * @var TaxRateFactory
     */
    protected $taxRateFactory;

    /**
     * InstallCmsPageData constructor
     *
     * @param SampleDataContext $sampleDataContext
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param ReadCsvData $readCsvData
     * @param SearchCriteriaBuilderFactory $criteriaBuilderFactory
     * @param EavSetup $eavSetup
     * @param TaxRateRepositoryInterface $taxRateRepository
     * @param TaxRateFactory $taxRateFactory
     */
    public function __construct(
        SampleDataContext $sampleDataContext,
        ModuleDataSetupInterface $moduleDataSetup,
        ReadCsvData $readCsvData,
        SearchCriteriaBuilderFactory $criteriaBuilderFactory,
        EavSetup $eavSetup,
        TaxRateRepositoryInterface $taxRateRepository,
        TaxRateFactory $taxRateFactory
    ) {
        $this->csvReader = $sampleDataContext->getCsvReader();
        $this->readCsvData = $readCsvData;
        $this->moduleDataSetup = $moduleDataSetup;
        $this->criteriaBuilderFactory = $criteriaBuilderFactory;
        $this->eavSetup = $eavSetup;
        $this->taxRateFactory = $taxRateFactory;
        $this->taxRateRepository = $taxRateRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $fixtureData = $this->readCsvData->readTaxRateCsv();

        $this->moduleDataSetup->startSetup();

        if (file_exists($fixtureData)) {
            $rows = $this->csvReader->getData($fixtureData);
            $header = array_shift($rows);

            $taxRateCodes = [];
            foreach ($rows as $taxRateCode) {
                $taxRateCode = array_combine($header, $taxRateCode);
                array_push($taxRateCodes, $taxRateCode[self::CODE]);
            }

            $criteriaBuilder = $this->criteriaBuilderFactory->create();
            $criteriaBuilder->addFilter(self::CODE, $taxRateCodes, 'in');
            $criteria = $criteriaBuilder->create();
            $taxRates = $this->taxRateRepository->getList($criteria)->getItems();

            foreach ($taxRates as $taxRate) {
                $taxRates[$taxRate->getCode()] = $taxRate;
            }

            foreach ($rows as $row) {
                $row = array_combine($header, $row);

                $model = isset($taxRates[$row[self::CODE]]) ? $taxRates[$row[self::CODE]] : null;
                if (!$model) {
                    $model = $this->taxRateFactory->create();
                }

                $model->setCode($row[self::CODE])
                    ->setTaxCountryId($row[self::TAX_COUNTRY_ID])
                    ->setTaxRegionId($row[self::TAX_REGION_ID])
                    ->setTaxPostcode($row[self::TAX_POSTCODE])
                    ->setZipIsRange($row[self::ZIP_IS_RANGE])
                    ->setRate($row[self::RATE]);

                $this->taxRateRepository->save($model);
            }

            $this->moduleDataSetup->endSetup();
        }
    }

    /**
     * Get Tax Rate Ids
     *
     * @return array
     */
    public function getTaxRateIds()
    {
        $taxRateData = $this->readCsvData->readTaxRateCsv();

        if (file_exists($taxRateData)) {
            $rows = $this->csvReader->getData($taxRateData);
            $header = array_shift($rows);

            $taxRateCodes = [];
            foreach ($rows as $row) {
                $row = array_combine($header, $row);
                array_push($taxRateCodes, $row[self::CODE]);
            }

            $criteriaBuilder = $this->criteriaBuilderFactory->create();
            $criteriaBuilder->addFilter(self::CODE, $taxRateCodes, 'in');
            $criteria = $criteriaBuilder->create();
            $taxRates = $this->taxRateRepository->getList($criteria)->getItems();

            $taxRateIds = [];
            foreach ($taxRates as $taxRate) {
                array_push($taxRateIds, $taxRate->getId());
            }
        }

        return $taxRateIds;
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }
}
