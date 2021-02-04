<?php
/**
 * SetupPatch Product Data
 *
 * @category  Smile
 * @package   Smile\Attribute
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */

namespace Smile\Catalog\Setup\Patch\Data;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Product\Type;
use Magento\Catalog\Model\Product\Visibility;
use Magento\CatalogInventory\Api\Data\StockItemInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\File\Csv;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\SampleData\Context as SampleDataContext;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\InventoryApi\Api\Data\SourceItemInterfaceFactory;
use Magento\InventoryApi\Api\SourceItemRepositoryInterface;
use Magento\Store\Model\Store;
use Magento\Tax\Helper\Data as TaxDataHelper;
use Smile\Catalog\Setup\Patch\ReadCsvData;

/**
 * Class InstallUniqueProduct
 */
class InstallCategory implements DataPatchInterface
{
    /**#@+
     * Category type
     */
    const ROOT = 'root';
    const SUB = 'sub';
    /**#@-*/

    /**#@+
     * Category columns
     */
    const NAME = 'name';
    const DESCRIPTION = 'description';
    const CREATED_AT = 'created_at';
    const CATEGORY_TYPE = 'category_type';
    const INCLUDE_IN_MENU = 'include_in_menu';
    const IS_ACTIVE = 'is_active';
    /**#@-*/

    /**
     * Product Factory
     *
     * @var CategoryFactory
     */
    protected $categoryFactory;

    /**
     * Product Repository
     *
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;

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
     * Date Time
     *
     * @var DateTime
     */
    protected $dateTime;

    /**
     * State
     *
     * @var State
     */
    protected $state;

    /**
     * Stock Interface
     *
     * @var StockInterfaceFactory
     */
    protected $stockInterface;

    /**
     * Source Item Interface Factory
     *
     * @var SourceItemInterfaceFactory
     */
    protected $sourceItemInterfaceFactory;

    /**
     * Source Item Repository
     *
     * @var SourceItemRepositoryInterface
     */
    protected $sourceItemRepository;

    /**
     * Tax Class Data
     *
     * @var InstallTaxClass
     */
    protected $taxClass;

    /**
     * Tax Data Helper
     *
     * @var TaxDataHelper
     */
    protected $taxDataHelper;

    /**
     * InstallCmsPageData constructor
     *
     * @param SampleDataContext $sampleDataContext
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CategoryFactory $categoryFactory
     * @param CategoryRepositoryInterface $categoryRepository
     * @param ReadCsvData $readCsvData
     * @param SearchCriteriaBuilderFactory $criteriaBuilderFactory
     * @param EavSetup $eavSetup
     * @param DateTime $dateTime
     * @param State $state
     * @param SourceItemInterfaceFactory $sourceItemInterfaceFactory
     * @param SourceItemRepositoryInterface $sourceItemRepository
     * @param InstallTaxClass $taxClass
     * @param TaxDataHelper $taxDataHelper
     */
    public function __construct(
        SampleDataContext $sampleDataContext,
        CategoryFactory $categoryFactory,
        CategoryRepositoryInterface $categoryRepository,
        ModuleDataSetupInterface $moduleDataSetup,
        ReadCsvData $readCsvData,
        SearchCriteriaBuilderFactory $criteriaBuilderFactory,
        EavSetup $eavSetup,
        DateTime $dateTime,
        State $state,
        SourceItemInterfaceFactory $sourceItemInterfaceFactory,
        SourceItemRepositoryInterface $sourceItemRepository,
        InstallTaxClass $taxClass,
        TaxDataHelper $taxDataHelper
    ) {
        $this->csvReader = $sampleDataContext->getCsvReader();
        $this->readCsvData = $readCsvData;
        $this->moduleDataSetup = $moduleDataSetup;
        $this->categoryFactory = $categoryFactory;
        $this->categoryRepository = $categoryRepository;
        $this->criteriaBuilderFactory = $criteriaBuilderFactory;
        $this->eavSetup = $eavSetup;
        $this->dateTime = $dateTime;
        $this->state = $state;
        $this->sourceItemInterfaceFactory = $sourceItemInterfaceFactory;
        $this->sourceItemRepository = $sourceItemRepository;
        $this->taxClass = $taxClass;
        $this->taxDataHelper = $taxDataHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $fixtureData = $this->readCsvData->readProductCsv();

        $this->moduleDataSetup->startSetup();

        if (file_exists($fixtureData)) {
            $rows = $this->csvReader->getData($fixtureData);
            $header = array_shift($rows);

            $this->state->setAreaCode(Area::AREA_FRONTEND);

            $productCodes = [];
            foreach ($rows as $productCode) {
                $productCode = array_combine($header, $productCode);
                array_push($productCodes, $productCode[self::NAME]);
            }

            $criteriaBuilder = $this->criteriaBuilderFactory->create();
            $criteriaBuilder->addFilter(self::NAME, $productCodes, 'in');
            $criteria = $criteriaBuilder->create();
            $products = $this->categoryRepository->getList($criteria)->getItems();

            foreach ($products as $product) {
                $products[$product->getSku()] = $product;
            }

            foreach ($rows as $row) {
                $row = array_combine($header, $row);

                $model = isset($products[$row[self::NAME]]) ? $products[$row[self::NAME]] : null;
                if (!$model) {
                    $model = $this->categoryFactory->create();
                }

                $model->setName($row[self::NAME])
                    ->setIsActive($row[self::IS_ACTIVE])
                    ->setData(self::DESCRIPTION, $row[self::DESCRIPTION])
                    ->setParentId($row[self::CATEGORY_TYPE] == self::ROOT ? Category::ROOT_CATEGORY_ID : Category::TREE_ROOT_ID)
                    ->setStoreId(Store::DEFAULT_STORE_ID)
                    ->setIncludeInMenu($row[self::INCLUDE_IN_MENU])
                    ->setCreatedAt($row[self::CREATED_AT])
                    ->setUpdatedAt($this->dateTime->gmtDate());

                $this->categoryRepository->save($model);
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
