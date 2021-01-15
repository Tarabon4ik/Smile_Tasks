<?php
/**
 * SetupPatch Cms Block Data
 *
 * @category  Smile
 * @package   Smile\Contract
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */

namespace Smile\Contract\Setup\Patch\Data;

use Magento\Cms\Model\BlockFactory;
use Magento\Cms\Model\BlockRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\File\Csv;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\SampleData\Context as SampleDataContext;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Smile\Contract\Setup\Patch\ReadCmsData;

/**
 * Class InstallCmsPageData
 */
class InstallCmsBlockData implements DataPatchInterface
{
    /**
     * Csv reader
     *
     * @var Csv
     */
    protected $csvReader;

    /**
     * Read CmsData
     *
     * @var ReadCmsData
     */
    protected $readCmsData;

    /**
     * Date Time
     *
     * @var DateTime
     */
    protected $dateTime;

    /**
     * Module Data Setup
     *
     * @var ModuleDataSetupInterface
     */
    protected $moduleDataSetup;

    /**
     * Block Factory
     *
     * @var BlockFactory
     */
    protected $blockFactory;

    /**
     * Block Repository
     *
     * @var BlockRepository
     */
    protected $blockRepository;

    /**
     * Search Criteria Builder
     *
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

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
     * @param ReadCmsData $readCmsData
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param DateTime $dateTime
     * @param BlockFactory $blockFactory
     * @param BlockRepository $blockRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SearchCriteriaBuilderFactory $criteriaBuilderFactory
     */
    public function __construct(
        SampleDataContext $sampleDataContext,
        ReadCmsData $readCmsData,
        ModuleDataSetupInterface $moduleDataSetup,
        DateTime $dateTime,
        BlockFactory $blockFactory,
        BlockRepository $blockRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SearchCriteriaBuilderFactory $criteriaBuilderFactory
    ) {
        $this->csvReader = $sampleDataContext->getCsvReader();
        $this->readCmsData = $readCmsData;
        $this->moduleDataSetup = $moduleDataSetup;
        $this->dateTime = $dateTime;
        $this->blockFactory = $blockFactory;
        $this->blockRepository = $blockRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->criteriaBuilderFactory = $criteriaBuilderFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $fixtureFileName = $this->readCmsData->readCmsBlockCsv();

        $this->moduleDataSetup->startSetup();

        if (file_exists($fixtureFileName)) {
            $rows = $this->csvReader->getData($fixtureFileName);
            $header = array_shift($rows);

            $pageIdentifiers = [];
            foreach ($rows as $identifierRow) {
                $identifierRow = array_combine($header, $identifierRow);
                array_push($pageIdentifiers, $identifierRow['identifier']);
            }

            $criteriaBuilder = $this->criteriaBuilderFactory->create();
            $criteriaBuilder->addFilter('identifier', $pageIdentifiers, 'in');
            $criteria = $criteriaBuilder->create();
            $cmsBlocks = $this->blockRepository->getList($criteria)->getItems();

            foreach ($cmsBlocks as $cmsBlock) {
                $cmsBlocks[$cmsBlock->getId()] = $cmsBlock->getIdentifier();
            }

            foreach ($rows as $row) {
                $row = array_combine($header, $row);

                if (in_array($row['identifier'], $cmsBlocks) == false) {
                    $model = $this->blockFactory->create();
                    $model->setTitle($row['title'])
                        ->setIdentifier($row['identifier'])
                        ->setContent($row['content'])
                        ->setCreationTime($row['creation_time'])
                        ->setUpdateTime($this->dateTime->gmtDate())
                        ->setIsActive($row['is_active']);
                    $this->blockRepository->save($model);
                } else {
                    $model = $this->blockRepository->getById(array_search($row['identifier'], $cmsBlocks));
                    $model->setTitle($row['title'])
                        ->setIdentifier($row['identifier'])
                        ->setContent($row['content'])
                        ->setCreationTime($row['creation_time'])
                        ->setUpdateTime($this->dateTime->gmtDate())
                        ->setIsActive($row['is_active']);
                    $this->blockRepository->save($model);
                }
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
