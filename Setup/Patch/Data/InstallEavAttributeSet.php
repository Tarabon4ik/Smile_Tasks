<?php
/**
 * SetupPatch EAV Attribute Set Data
 *
 * @category  Smile
 * @package   Smile\Attribute
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */

namespace Smile\Attribute\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Api\AttributeSetRepositoryInterface;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Eav\Setup\EavSetup;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\File\Csv;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\SampleData\Context as SampleDataContext;
use Smile\Attribute\Setup\Patch\ReadCsvData;

/**
 * Class InstallEavAttributeSetData
 */
class InstallEavAttributeSet implements DataPatchInterface
{
    /**#@+
     * Eav attribute set columns
     */
    const ATTRIBUTE_SET_NAME = 'attribute_set_name';
    const SORT_ORDER = 'sort_order';
    /**#@-*/

    /**
     * Attribute Set Factory
     *
     * @var AttributeSetFactory
     */
    protected $attributeSetFactory;

    /**
     * Eav Setup
     *
     * @var EavSetup
     */
    protected $eavSetup;

    /**
     * Attribute Set Repository
     *
     * @var AttributeSetRepositoryInterface
     */
    protected $attributeSetRepository;

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
     * InstallCmsPageData constructor
     *
     * @param SampleDataContext $sampleDataContext
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param AttributeSetFactory $attributeSetFactory
     * @param ReadCsvData $readCsvData
     * @param AttributeSetRepositoryInterface $attributeSetRepository
     * @param SearchCriteriaBuilderFactory $criteriaBuilderFactory
     * @param EavSetup $eavSetup
     */
    public function __construct(
        SampleDataContext $sampleDataContext,
        ModuleDataSetupInterface $moduleDataSetup,
        AttributeSetFactory $attributeSetFactory,
        ReadCsvData $readCsvData,
        AttributeSetRepositoryInterface $attributeSetRepository,
        SearchCriteriaBuilderFactory $criteriaBuilderFactory,
        EavSetup $eavSetup
    ) {
        $this->csvReader = $sampleDataContext->getCsvReader();
        $this->readCsvData = $readCsvData;
        $this->moduleDataSetup = $moduleDataSetup;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->attributeSetRepository = $attributeSetRepository;
        $this->criteriaBuilderFactory = $criteriaBuilderFactory;
        $this->eavSetup = $eavSetup;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $fixtureData = $this->readCsvData->readEavAttributeSetCsv();

        $this->moduleDataSetup->startSetup();

        if (file_exists($fixtureData)) {
            $rows = $this->csvReader->getData($fixtureData);
            $header = array_shift($rows);

            $attributeSetNames = [];
            foreach ($rows as $attributeSetRow) {
                $attributeSetRow = array_combine($header, $attributeSetRow);
                array_push($attributeSetNames, $attributeSetRow[self::ATTRIBUTE_SET_NAME]);
            }

            $criteriaBuilder = $this->criteriaBuilderFactory->create();
            $criteriaBuilder->addFilter(self::ATTRIBUTE_SET_NAME, $attributeSetNames, 'in');
            $criteria = $criteriaBuilder->create();
            $attributeSets = $this->attributeSetRepository->getList($criteria)->getItems();

            foreach ($attributeSets as $attributeSet) {
                $attributeSets[$attributeSet->getAttributeSetName()] = $attributeSet;
            }

            foreach ($rows as $row) {
                $row = array_combine($header, $row);

                $model = isset($attributeSets[$row[self::ATTRIBUTE_SET_NAME]]) ? $attributeSets[$row[self::ATTRIBUTE_SET_NAME]] : null;
                if (!$model) {
                    $model = $this->attributeSetFactory->create();
                }

                $entityTypeId = $this->eavSetup->getEntityTypeId(Product::ENTITY);
                $attributeSetId = $this->eavSetup->getDefaultAttributeSetId($entityTypeId);

                $model->setAttributeSetName($row[self::ATTRIBUTE_SET_NAME])
                    ->setEntityTypeId($entityTypeId)
                    ->setSortOrder($row[self::SORT_ORDER])
                    ->validate();

                $this->attributeSetRepository->save($model);

                $model->initFromSkeleton($attributeSetId);
                $this->attributeSetRepository->save($model);
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
