<?php
/**
 * SetupPatch Tax Class
 *
 * @category  Smile
 * @package   Smile\Attribute
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */

namespace Smile\Attribute\Setup\Patch\Data;

use Magento\Eav\Setup\EavSetup;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\File\Csv;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\SampleData\Context as SampleDataContext;
use Magento\Tax\Api\TaxClassRepositoryInterface;
use Magento\Tax\Model\ClassModelFactory;
use Smile\Attribute\Setup\Patch\ReadCsvData;

/**
 * Class InstallTaxClass
 */
class InstallTaxClass implements DataPatchInterface
{
    /**#@+
     * Tax Class Columns
     */
    const CLASS_NAME = 'class_name';
    const CLASS_TYPE = 'class_type';
    /**#@-*/

    /**#@+
     * Class Types
     */
    const TYPE_CUSTOMER = 'CUSTOMER';
    const TYPE_PRODUCT = 'PRODUCT';
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
     * Tax Class Repository
     *
     * @var TaxClassRepositoryInterface
     */
    protected $taxClassRepository;

    /**
     * Class Model Factory
     *
     * @var ClassModelFactory
     */
    protected $taxClassFactory;

    /**
     * Filter Builder
     *
     * @var FilterBuilder
     */
    protected $filterBuilder;

    /**
     * InstallCmsPageData constructor
     *
     * @param SampleDataContext $sampleDataContext
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param ReadCsvData $readEavData
     * @param SearchCriteriaBuilderFactory $criteriaBuilderFactory
     * @param EavSetup $eavSetup
     * @param TaxClassRepositoryInterface $taxClassRepository
     * @param ClassModelFactory $taxClassFactory
     * @param FilterBuilder $filterBuilder
     */
    public function __construct(
        SampleDataContext $sampleDataContext,
        ModuleDataSetupInterface $moduleDataSetup,
        ReadCsvData $readCsvData,
        SearchCriteriaBuilderFactory $criteriaBuilderFactory,
        EavSetup $eavSetup,
        TaxClassRepositoryInterface $taxClassRepository,
        ClassModelFactory $taxClassFactory,
        FilterBuilder $filterBuilder
    ) {
        $this->csvReader = $sampleDataContext->getCsvReader();
        $this->readCsvData = $readCsvData;
        $this->moduleDataSetup = $moduleDataSetup;
        $this->criteriaBuilderFactory = $criteriaBuilderFactory;
        $this->eavSetup = $eavSetup;
        $this->taxClassFactory = $taxClassFactory;
        $this->taxClassRepository = $taxClassRepository;
        $this->filterBuilder = $filterBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $fixtureData = $this->readCsvData->readTaxClassCsv();

        $this->moduleDataSetup->startSetup();

        if (file_exists($fixtureData)) {
            $rows = $this->csvReader->getData($fixtureData);
            $header = array_shift($rows);

            $taxClassNames = [];
            foreach ($rows as $taxClassName) {
                $taxClassName = array_combine($header, $taxClassName);
                array_push($taxClassNames, $taxClassName[self::CLASS_NAME]);
            }

            $criteriaBuilder = $this->criteriaBuilderFactory->create();
            $criteriaBuilder->addFilter(self::CLASS_NAME, $taxClassNames, 'in');
            $criteria = $criteriaBuilder->create();
            $taxClasses = $this->taxClassRepository->getList($criteria)->getItems();

            foreach ($taxClasses as $taxClass) {
                $taxClasses[$taxClass->getClassName()] = $taxClass;
            }

            foreach ($rows as $row) {
                $row = array_combine($header, $row);

                $model = isset($taxClasses[$row[self::CLASS_NAME]]) ? $taxClasses[$row[self::CLASS_NAME]] : null;
                if (!$model) {
                    $model = $this->taxClassFactory->create();
                }

                $model->setClassName($row[self::CLASS_NAME])
                    ->setClassType($row[self::CLASS_TYPE]);

                $this->taxClassRepository->save($model);
            }

            $this->moduleDataSetup->endSetup();
        }
    }

    /**
     * Get Customer Tax Class Ids
     *
     * @return array
     */
    public function getCustomerTaxClassIds()
    {
        $taxRateData = $this->readCsvData->readTaxClassCsv();

        if (file_exists($taxRateData)) {
            $rows = $this->csvReader->getData($taxRateData);
            $header = array_shift($rows);

            $taxClassNames = [];
            foreach ($rows as $taxClassName) {
                $taxClassName = array_combine($header, $taxClassName);
                array_push($taxClassNames, $taxClassName[self::CLASS_NAME]);
            }

            $criteriaBuilder = $this->criteriaBuilderFactory->create();

            $criteriaBuilder->addFilter(self::CLASS_NAME, $taxClassNames, 'in');
            $criteriaBuilder->addFilter(self::CLASS_TYPE, self::TYPE_CUSTOMER, 'eq');

            $criteria = $criteriaBuilder->create();

            $taxClasses = $this->taxClassRepository->getList($criteria)->getItems();

            $taxClassIds = [];
            foreach ($taxClasses as $taxClass) {
                array_push($taxClassIds, $taxClass->getClassId());
            }
        }

        return $taxClassIds;
    }

    /**
     * Get Product Tax Class Ids
     *
     * @return array
     */
    public function getProductTaxClassIds()
    {
        $taxRateData = $this->readCsvData->readTaxClassCsv();

        if (file_exists($taxRateData)) {
            $rows = $this->csvReader->getData($taxRateData);
            $header = array_shift($rows);

            $taxClassNames = [];
            foreach ($rows as $taxClassName) {
                $taxClassName = array_combine($header, $taxClassName);
                array_push($taxClassNames, $taxClassName[self::CLASS_NAME]);
            }

            $criteriaBuilder = $this->criteriaBuilderFactory->create();

            $criteriaBuilder->addFilter(self::CLASS_NAME, $taxClassNames, 'in');
            $criteriaBuilder->addFilter(self::CLASS_TYPE, self::TYPE_PRODUCT, 'eq');

            $criteria = $criteriaBuilder->create();

            $taxClasses = $this->taxClassRepository->getList($criteria)->getItems();

            $taxClassIds = [];
            foreach ($taxClasses as $taxClass) {
                array_push($taxClassIds, $taxClass->getClassId());
            }
        }

        return $taxClassIds;
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
