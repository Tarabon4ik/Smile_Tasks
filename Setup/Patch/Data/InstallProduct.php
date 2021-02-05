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

use Magento\Catalog\Api\CategoryLinkManagementInterface as CategoryLinkInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Product\Type;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ProductFactory;
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
use Smile\Catalog\Block\Category\ListCategoryProducts;
use Smile\Catalog\Setup\Patch\ReadCsvData;

/**
 * Class InstallUniqueProduct
 */
class InstallProduct implements DataPatchInterface
{
    /**#@+
     * Tax product attribute
     */
    const TAX_CLASS_ID = 'tax_class_id';
    const TAX_CLASS_NAME = 'class_name';
    /**#@-*/

    /**#@+
     * Product columns
     */
    const ATTRIBUTE_SET_NAME = 'attribute_set_name';
    const NAME = 'name';
    const SKU = 'sku';
    const PRICE = 'price';
    const FINAL_PRICE = 'final_price';
    const QTY = 'qty';
    const CREATED_AT = 'created_at';
    const CATEGORY = 'category';
    /**#@-*/

    /**
     * Product Factory
     *
     * @var ProductFactory
     */
    protected $productFactory;

    /**
     * Product Repository
     *
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

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
     * Tax Data Helper
     *
     * @var TaxDataHelper
     */
    protected $taxDataHelper;

    /**
     * Category Link Interface
     *
     * @var CategoryLinkInterface
     */
    protected $categoryLinkInterface;

    /**
     * List Category Products Block
     *
     * @var ListCategoryProducts
     */
    protected $listCategoryProducts;

    /**
     * InstallCmsPageData constructor
     *
     * @param SampleDataContext $sampleDataContext
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param ProductFactory $productFactory
     * @param ProductRepositoryInterface $productRepository
     * @param ReadCsvData $readCsvData
     * @param SearchCriteriaBuilderFactory $criteriaBuilderFactory
     * @param EavSetup $eavSetup
     * @param DateTime $dateTime
     * @param State $state
     * @param SourceItemInterfaceFactory $sourceItemInterfaceFactory
     * @param SourceItemRepositoryInterface $sourceItemRepository
     * @param TaxDataHelper $taxDataHelper
     * @param CategoryLinkInterface $categoryLinkInterface
     * @param ListCategoryProducts $listCategoryProducts
     */
    public function __construct(
        SampleDataContext $sampleDataContext,
        ProductFactory $productFactory,
        ProductRepositoryInterface $productRepository,
        ModuleDataSetupInterface $moduleDataSetup,
        ReadCsvData $readCsvData,
        SearchCriteriaBuilderFactory $criteriaBuilderFactory,
        EavSetup $eavSetup,
        DateTime $dateTime,
        State $state,
        SourceItemInterfaceFactory $sourceItemInterfaceFactory,
        SourceItemRepositoryInterface $sourceItemRepository,
        TaxDataHelper $taxDataHelper,
        CategoryLinkInterface $categoryLinkInterface,
        ListCategoryProducts $listCategoryProducts
    ) {
        $this->csvReader = $sampleDataContext->getCsvReader();
        $this->readCsvData = $readCsvData;
        $this->moduleDataSetup = $moduleDataSetup;
        $this->productFactory = $productFactory;
        $this->productRepository = $productRepository;
        $this->criteriaBuilderFactory = $criteriaBuilderFactory;
        $this->eavSetup = $eavSetup;
        $this->dateTime = $dateTime;
        $this->state = $state;
        $this->sourceItemInterfaceFactory = $sourceItemInterfaceFactory;
        $this->sourceItemRepository = $sourceItemRepository;
        $this->taxDataHelper = $taxDataHelper;
        $this->categoryLinkInterface = $categoryLinkInterface;
        $this->listCategoryProducts = $listCategoryProducts;
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

            try {
                $this->state->setAreaCode(Area::AREA_FRONTEND);
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
            }

            $productCodes = [];
            foreach ($rows as $productCode) {
                $productCode = array_combine($header, $productCode);
                array_push($productCodes, $productCode[self::SKU]);
            }

            $criteriaBuilder = $this->criteriaBuilderFactory->create();
            $criteriaBuilder->addFilter(self::SKU, $productCodes, 'in');
            $criteria = $criteriaBuilder->create();
            $products = $this->productRepository->getList($criteria)->getItems();

            foreach ($products as $product) {
                $products[$product->getSku()] = $product;
            }

            foreach ($rows as $row) {
                $row = array_combine($header, $row);

                $model = isset($products[$row[self::SKU]]) ? $products[$row[self::SKU]] : null;
                if (!$model) {
                    $model = $this->productFactory->create();
                }

                $entityTypeId = $this->eavSetup->getEntityTypeId(Product::ENTITY);
                $attributeSetId = $this->eavSetup->getAttributeSetId($entityTypeId, $row[self::ATTRIBUTE_SET_NAME]);

                $model->setAttributeSetId($attributeSetId)
                    ->setTypeId(Type::TYPE_SIMPLE)
                    ->setName($row[self::NAME])
                    ->setCreatedAt($row[self::CREATED_AT])
                    ->setUpdatedAt($this->dateTime->gmtDate())
                    ->setSku($row[self::SKU])
                    ->setPrice($row[self::PRICE])
                    ->setFinalPrice($row[self::FINAL_PRICE])
                    ->setVisibility(Visibility::VISIBILITY_BOTH)
                    ->setStatus(Status::STATUS_ENABLED)
                    ->setQty($row[self::QTY])
                    ->setStockData([
                        StockItemInterface::USE_CONFIG_MANAGE_STOCK => 0,
                        StockItemInterface::MANAGE_STOCK => 1,
                        StockItemInterface::IS_IN_STOCK => 1,
                        StockItemInterface::QTY => $row[self::QTY]
                    ])
                    ->setStoreId(Store::DEFAULT_STORE_ID);

                $this->productRepository->save($model);

                $categoryIds = $this->listCategoryProducts->getAllChildren(true, $this->listCategoryProducts->getCategoryIdByName($row[self::CATEGORY]));
                $this->categoryLinkInterface->assignProductToCategories($row[self::SKU], $categoryIds);
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
