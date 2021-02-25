<?php
/**
 * SetupPatch Product Data
 *
 * @category  Smile
 * @package   Smile\Catalog
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */

namespace Smile\Catalog\Setup\Patch\Data;

use Magento\Catalog\Api\CategoryListInterface;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\File\Csv;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\SampleData\Context as SampleDataContext;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Store\Model\Store;
use Psr\Log\LoggerInterface;
use Smile\Catalog\Setup\Patch\ReadCsvData;

/**
 * Class InstallCategory
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
    const PARENT_CATEGORY_NAME = 'parent_category_name';
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
     * Category List
     *
     * @var CategoryListInterface
     */
    protected $categoryList;

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
     * Search criteria builder
     *
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * Filter builder
     *
     * @var FilterBuilder
     */
    protected $filterBuilder;

    /**
     * Logger Interface
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * InstallCmsPageData constructor
     *
     * @param SampleDataContext $sampleDataContext
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CategoryFactory $categoryFactory
     * @param CategoryRepositoryInterface $categoryRepository
     * @param ReadCsvData $readCsvData
     * @param SearchCriteriaBuilderFactory $criteriaBuilderFactory
     * @param DateTime $dateTime
     * @param State $state
     * @param CategoryListInterface $categoryList
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param FilterBuilder $filterBuilder
     * @param LoggerInterface $logger
     */
    public function __construct(
        SampleDataContext $sampleDataContext,
        CategoryFactory $categoryFactory,
        CategoryRepositoryInterface $categoryRepository,
        ModuleDataSetupInterface $moduleDataSetup,
        ReadCsvData $readCsvData,
        SearchCriteriaBuilderFactory $criteriaBuilderFactory,
        DateTime $dateTime,
        State $state,
        CategoryListInterface $categoryList,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder,
        LoggerInterface $logger
    ) {
        $this->csvReader = $sampleDataContext->getCsvReader();
        $this->readCsvData = $readCsvData;
        $this->moduleDataSetup = $moduleDataSetup;
        $this->categoryFactory = $categoryFactory;
        $this->categoryRepository = $categoryRepository;
        $this->criteriaBuilderFactory = $criteriaBuilderFactory;
        $this->dateTime = $dateTime;
        $this->state = $state;
        $this->categoryList = $categoryList;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $fixtureData = $this->readCsvData->readCategoryCsv();

        $this->moduleDataSetup->startSetup();

        if (file_exists($fixtureData)) {
            $rows = $this->csvReader->getData($fixtureData);
            $header = array_shift($rows);

            try {
                $this->state->setAreaCode(Area::AREA_FRONTEND);
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->logger->error('Area Code Conflict', ['exception' => $e]);
            }

            $productCodes = [];
            foreach ($rows as $productCode) {
                $productCode = array_combine($header, $productCode);
                array_push($productCodes, $productCode[self::NAME]);
            }

            $criteriaBuilder = $this->criteriaBuilderFactory->create();
            $criteriaBuilder->addFilter(self::NAME, $productCodes, 'in');
            $criteria = $criteriaBuilder->create();
            $categories = $this->categoryList->getList($criteria)->getItems();

            foreach ($categories as $category) {
                $categories[$category->getName()] = $category;
            }

            $categoriesToLoad = [];
            foreach ($rows as $row) {
                $row = array_combine($header, $row);
                if (!in_array($row[self::CATEGORY_TYPE], [self::ROOT, self::SUB])) {
                    $categoriesToLoad[] = $row[self::PARENT_CATEGORY_NAME];
                }
            }

            $categoryCollection = $this->getCategoryCollectionByNames($categoriesToLoad);

            $categoryIdByName = [];
            /** @var \Magento\Catalog\Model\Category $categoryEntity */
            foreach ($categoryCollection as $categoryEntity) {
                $categoryIdByName[$categoryEntity->getName()] = $categoryEntity->getCategoryId();
            }

            foreach ($rows as $row) {
                $row = array_combine($header, $row);

                $model = isset($categories[$row[self::NAME]]) ? $categories[$row[self::NAME]] : null;
                if (!$model) {
                    $model = $this->categoryFactory->create();
                }

                if ($row[self::CATEGORY_TYPE] == self::ROOT) {
                    $parentId = Category::ROOT_CATEGORY_ID;
                } elseif ($row[self::CATEGORY_TYPE] == self::SUB) {
                    $parentId = Category::TREE_ROOT_ID;
                } else {
                    $parentId = $categoryIdByName[$row[self::PARENT_CATEGORY_NAME]];
                }

                $model->setName($row[self::NAME])
                    ->setIsActive($row[self::IS_ACTIVE])
                    ->setData(self::DESCRIPTION, $row[self::DESCRIPTION])
                    ->setParentId($parentId)
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
     * Get Category Collection By Names
     *
     * @param array $categoryNames
     *
     * @return \Magento\Catalog\Api\Data\CategoryInterface[]
     */
    public function getCategoryCollectionByNames(array $categoryNames)
    {
        $categoryNames = array_unique($categoryNames);

        $filter = $this->filterBuilder
            ->setField('name')
            ->setValue($categoryNames)
            ->setConditionType('in')
            ->create();
        $searchCriteria = $this->searchCriteriaBuilder->addFilters([$filter])->create();

        return $this->categoryList->getList($searchCriteria)->getItems();
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
