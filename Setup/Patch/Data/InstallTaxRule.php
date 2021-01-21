<?php
/**
 * SetupPatch Tax Rule Data
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
use Magento\Tax\Api\TaxRuleRepositoryInterface;
use Magento\Tax\Model\Calculation\RuleFactory as TaxRuleFactory;
use Smile\Attribute\Setup\Patch\ReadCsvData;

/**
 * Class TaxRuleData
 */
class InstallTaxRule implements DataPatchInterface
{
    /**#@+
     * Tax Rule Columns
     */
    const CODE = 'code';
    const PRIORITY = 'priority';
    const CUSTOMER_TAX_CLASS_IDS = 'customer_tax_class_ids';
    const PRODUCT_TAX_CLASS_IDS = 'product_tax_class_ids';
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
     * Tax Rule Repository
     *
     * @var TaxRuleRepositoryInterface
     */
    protected $taxRuleRepository;

    /**
     * Tax Rule Factory
     *
     * @var TaxRuleFactory
     */
    protected $taxRuleFactory;

    /**
     * Tax Rate Data
     *
     * @var InstallTaxRate
     */
    protected $taxRateData;

    /**
     * Tax Class Data
     *
     * @var InstallTaxClass
     */
    protected $taxClass;

    /**
     * InstallCmsPageData constructor
     *
     * @param SampleDataContext $sampleDataContext
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param ReadCsvData $readEavData
     * @param SearchCriteriaBuilderFactory $criteriaBuilderFactory
     * @param EavSetup $eavSetup
     * @param TaxRuleRepositoryInterface $taxRuleRepository
     * @param TaxRuleFactory $taxRuleFactory
     * @param InstallTaxRate $taxRateData
     * @param InstallTaxClass $taxClass
     */
    public function __construct(
        SampleDataContext $sampleDataContext,
        ModuleDataSetupInterface $moduleDataSetup,
        ReadCsvData $readCsvData,
        SearchCriteriaBuilderFactory $criteriaBuilderFactory,
        EavSetup $eavSetup,
        TaxRuleRepositoryInterface $taxRuleRepository,
        TaxRuleFactory $taxRuleFactory,
        InstallTaxRate $taxRateData,
        InstallTaxClass $taxClass
    ) {
        $this->csvReader = $sampleDataContext->getCsvReader();
        $this->readCsvData = $readCsvData;
        $this->moduleDataSetup = $moduleDataSetup;
        $this->criteriaBuilderFactory = $criteriaBuilderFactory;
        $this->eavSetup = $eavSetup;
        $this->taxRuleFactory = $taxRuleFactory;
        $this->taxRuleRepository = $taxRuleRepository;
        $this->taxRateData = $taxRateData;
        $this->taxClass = $taxClass;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $fixtureData = $this->readCsvData->readTaxRuleCsv();

        $taxRateData = $this->readCsvData->readTaxRateCsv();
        if (file_exists($taxRateData)) {
            $rows = $this->csvReader->getData($taxRateData);
            $header = array_shift($rows);
            foreach ($rows as $row) {
                $row = array_combine($header, $row);
            }
        }

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
            $taxRates = $this->taxRuleRepository->getList($criteria)->getItems();

            foreach ($taxRates as $taxRate) {
                $taxRates[$taxRate->getCode()] = $taxRate;
            }

            foreach ($rows as $row) {
                $row = array_combine($header, $row);

                $model = isset($taxRates[$row[self::CODE]]) ? $taxRates[$row[self::CODE]] : null;
                if (!$model) {
                    $model = $this->taxRuleFactory->create();
                }

                $model->setCode($row[self::CODE])
                    ->setPriority($row[self::PRIORITY])
                    ->setCustomerTaxClassIds($this->taxClass->getCustomerTaxClassIds())
                    ->setProductTaxClassIds($this->taxClass->getProductTaxClassIds())
                    ->setTaxRateIds($this->taxRateData->getTaxRateIds());

                $this->taxRuleRepository->save($model);
            }

            $this->moduleDataSetup->endSetup();
        }
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
