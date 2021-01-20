<?php
/**
 * SetupPatch Product Data
 *
 * @category  Smile
 * @package   Smile\Attribute
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */

namespace Smile\Attribute\Setup\Patch\Data;

use Magento\Catalog\Api\Data\ProductTierPriceInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Product\Type;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
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
use Magento\Store\Model\Store;
use Smile\Attribute\Setup\Patch\ReadCsvData;

/**
 * Class InstallUniqueProduct
 */
class InstallUniqueProduct implements DataPatchInterface
{
    /**#@+
     * Product attribute codes
     */
    const VOLTAGE = 'voltage';
    const OUTPUTS = 'outputs';
    const INPUTS = 'inputs';
    const REVERB = 'reverb';
    const CHANNELS = 'channels';
    const PREAMP_VALVES = 'preamp_valves';
    const SPEAKER_CONFIGURATION = 'speaker_configuration';
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
        State $state
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

                $customAttributes = [
                    self::VOLTAGE => $row[self::VOLTAGE],
                    self::OUTPUTS => $row[self::OUTPUTS],
                    self::INPUTS => $row[self::INPUTS],
                    self::REVERB => $row[self::REVERB],
                    self::CHANNELS => $row[self::CHANNELS],
                    self::PREAMP_VALVES => $row[self::PREAMP_VALVES],
                    self::SPEAKER_CONFIGURATION => $row[self::SPEAKER_CONFIGURATION]
                ];

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
                        StockItemInterface::MIN_SALE_QTY => 1,
                        StockItemInterface::MAX_SALE_QTY => 10,
                        StockItemInterface::IS_IN_STOCK => 1,
                        StockItemInterface::QTY => $row[self::QTY]
                    ])
                    ->setStoreId(Store::DEFAULT_STORE_ID)
                    ->setCustomAttributes($customAttributes);

                $this->productRepository->save($model);
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
