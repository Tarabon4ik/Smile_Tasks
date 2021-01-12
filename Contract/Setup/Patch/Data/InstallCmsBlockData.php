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
use Magento\Cms\Model\ResourceModel\Block as CmsBlockResourceModel;
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
     * CMS Page ResourceModel
     *
     * @var CmsBlockResourceModel
     */
    protected $cmsBlockResourceModel;

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
     * @var BlockFactory
     */
    protected $blockFactory;

    /**
     * @var BlockRepository
     */
    protected $blockRepository;

    /**
     * InstallCmsPageData constructor
     *
     * @param SampleDataContext $sampleDataContext
     * @param ReadCmsData $readCmsData
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CmsBlockResourceModel $cmsBlockResourceModel
     * @param DateTime $dateTime
     * @param BlockFactory $blockFactory
     * @param BlockRepository $blockRepository
     */
    public function __construct(
        SampleDataContext $sampleDataContext,
        ReadCmsData $readCmsData,
        ModuleDataSetupInterface $moduleDataSetup,
        CmsBlockResourceModel $cmsBlockResourceModel,
        DateTime $dateTime,
        BlockFactory $blockFactory,
        BlockRepository $blockRepository
    ) {
        $this->csvReader = $sampleDataContext->getCsvReader();
        $this->readCmsData = $readCmsData;
        $this->moduleDataSetup = $moduleDataSetup;
        $this->cmsBlockResourceModel = $cmsBlockResourceModel;
        $this->dateTime = $dateTime;
        $this->blockFactory = $blockFactory;
        $this->blockRepository = $blockRepository;
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

            foreach ($rows as $row) {
                $data = [];
                foreach ($row as $key => $value) {
                    $data[$header[$key]] = $value;
                }

                $blockIdentifier = $data['identifier'];

                $model = $this->blockFactory->create();
                $cmsBlock = $model->load($blockIdentifier);

                if ($this->cmsBlockResourceModel->getIsUniqueBlockToStores($cmsBlock) == true) {
                    $model->setTitle($data['title'])
                        ->setIdentifier($data['identifier'])
                        ->setContent($data['content'])
                        ->setCreationTime($data['creation_time'])
                        ->setUpdateTime($this->dateTime->gmtDate())
                        ->setIsActive($data['identifier']);
                    $this->blockRepository->save($model);
                } elseif ($this->cmsBlockResourceModel->getIsUniqueBlockToStores($cmsBlock) == false) {
                    $this->cmsBlockResourceModel->delete($cmsBlock);
                    $model->setTitle($data['title'])
                        ->setIdentifier($data['identifier'])
                        ->setContent($data['content'])
                        ->setCreationTime($data['content'])
                        ->setUpdateTime($this->dateTime->gmtDate())
                        ->setIsActive($data['identifier']);
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
