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

use Magento\Catalog\Api\ProductAttributeManagementInterface;
use Magento\Catalog\Model\Product;
use Magento\Eav\Api\AttributeRepositoryInterface;
use Magento\Eav\Model\Entity\AttributeFactory;
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
class InstallEavProductAttribute implements DataPatchInterface
{
    /**#@+
     * Eav Product Attribute columns
     */
    const ATTRIBUTE_CODE = 'attribute_code';
    const BACKEND_TYPE = 'backend_type';
    const FRONTEND_INPUT = 'frontend_input';
    const FRONTEND_LABEL = 'frontend_label';
    const IS_REQUIRED = 'is_required';
    const IS_USER_DEFINED = 'is_user_defined';
    const IS_UNIQUE = 'is_unique';
    const ATTRIBUTE_SET_NAME = 'attribute_set_name';
    const SOURCE_MODEL = 'source_model';
    const BACKEND_MODEL = 'backend_model';
    /**#@-*/

    /**
     * Attribute Factory
     *
     * @var AttributeFactory
     */
    protected $attributeFactory;

    /**
     * Eav Setup
     *
     * @var EavSetup
     */
    protected $eavSetup;

    /**
     * Attribute Set Repository
     *
     * @var AttributeRepositoryInterface
     */
    protected $attributeRepository;

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
     * Product Attribute Management Interface
     *
     * @var ProductAttributeManagementInterface
     */
    private $productAttributeManagement;

    /**
     * InstallCmsPageData constructor
     *
     * @param SampleDataContext $sampleDataContext
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param AttributeFactory $attributeFactory
     * @param ReadCsvData $readCsvData
     * @param AttributeRepositoryInterface $attributeRepository
     * @param SearchCriteriaBuilderFactory $criteriaBuilderFactory
     * @param EavSetup $eavSetup
     * @param ProductAttributeManagementInterface $productAttributeManagement
     */
    public function __construct(
        SampleDataContext $sampleDataContext,
        ModuleDataSetupInterface $moduleDataSetup,
        AttributeFactory $attributeFactory,
        ReadCsvData $readCsvData,
        AttributeRepositoryInterface $attributeRepository,
        SearchCriteriaBuilderFactory $criteriaBuilderFactory,
        EavSetup $eavSetup,
        ProductAttributeManagementInterface $productAttributeManagement
    ) {
        $this->csvReader = $sampleDataContext->getCsvReader();
        $this->readCsvData = $readCsvData;
        $this->moduleDataSetup = $moduleDataSetup;
        $this->attributeFactory = $attributeFactory;
        $this->attributeRepository = $attributeRepository;
        $this->criteriaBuilderFactory = $criteriaBuilderFactory;
        $this->eavSetup = $eavSetup;
        $this->productAttributeManagement = $productAttributeManagement;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $fixtureData = $this->readCsvData->readEavAttributesCsv();

        $this->moduleDataSetup->startSetup();

        if (file_exists($fixtureData)) {
            $rows = $this->csvReader->getData($fixtureData);
            $header = array_shift($rows);

            $attributeCodes = [];
            foreach ($rows as $attributeCode) {
                $attributeCode = array_combine($header, $attributeCode);
                array_push($attributeCodes, $attributeCode[self::ATTRIBUTE_CODE]);
            }

            $criteriaBuilder = $this->criteriaBuilderFactory->create();
            $criteriaBuilder->addFilter(self::ATTRIBUTE_CODE, $attributeCodes, 'in');
            $criteria = $criteriaBuilder->create();
            $attributes = $this->attributeRepository->getList(Product::ENTITY, $criteria)->getItems();

            foreach ($attributes as $attribute) {
                $attributes[$attribute->getName()] = $attribute;
            }

            foreach ($rows as $row) {
                $row = array_combine($header, $row);

                $model = isset($attributes[$row[self::ATTRIBUTE_CODE]]) ? $attributes[$row[self::ATTRIBUTE_CODE]] : null;
                if (!$model) {
                    $model = $this->attributeFactory->create();
                }

                $entityTypeId = $this->eavSetup->getEntityTypeId(Product::ENTITY);
                $attributeSetId = $this->eavSetup->getAttributeSetId($entityTypeId, $row[self::ATTRIBUTE_SET_NAME]);

                $model->setBackendType($row[self::BACKEND_TYPE])
                    ->setAttributeSetId($attributeSetId)
                    ->setIsUnique($row[self::IS_UNIQUE])
                    ->setIsRequired($row[self::IS_REQUIRED])
                    ->setIsUserDefined($row[self::IS_USER_DEFINED])
                    ->setDefaultFrontendLabel($row[self::FRONTEND_LABEL])
                    ->setFrontendInput($row[self::FRONTEND_INPUT])
                    ->setSourceModel($row[self::SOURCE_MODEL])
                    ->setBackendModel($row[self::BACKEND_MODEL])
                    ->setAttributeCode($row[self::ATTRIBUTE_CODE])
                    ->setName($row[self::ATTRIBUTE_CODE])
                    ->setEntityTypeId($entityTypeId);

                $this->attributeRepository->save($model);

                $this->productAttributeManagement->assign(
                    $attributeSetId,
                    $this->eavSetup->getDefaultAttributeGroupId($entityTypeId, $attributeSetId),
                    $row['attribute_code'],
                    999
                );
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
