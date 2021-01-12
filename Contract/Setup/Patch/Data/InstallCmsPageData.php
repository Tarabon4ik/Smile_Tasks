<?php
/**
 * SetupPatch Cms Page Data
 *
 * @category  Smile
 * @package   Smile\Contract
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */

namespace Smile\Contract\Setup\Patch\Data;

use Magento\Cms\Model\PageFactory;
use Magento\Cms\Model\PageRepository;
use Magento\Cms\Model\ResourceModel\Page as CmsPageResourceModel;
use Magento\Framework\File\Csv;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\SampleData\Context as SampleDataContext;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Store\Model\Store;
use Smile\Contract\Setup\Patch\ReadCmsData;

/**
 * Class InstallCmsPageData
 */
class InstallCmsPageData implements DataPatchInterface
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
     * @var CmsPageResourceModel
     */
    protected $cmsPageResourceModel;

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
     * @var PageFactory
     */
    protected $pageFactory;

    /**
     * @var PageRepository
     */
    protected $pageRepository;

    /**
     * InstallCmsPageData constructor
     *
     * @param SampleDataContext $sampleDataContext
     * @param ReadCmsData $readCmsData
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CmsPageResourceModel $cmsPageResourceModel
     * @param DateTime $dateTime
     * @param PageFactory $pageFactory
     * @param PageRepository $pageRepository
     */
    public function __construct(
        SampleDataContext $sampleDataContext,
        ReadCmsData $readCmsData,
        ModuleDataSetupInterface $moduleDataSetup,
        CmsPageResourceModel $cmsPageResourceModel,
        DateTime $dateTime,
        PageFactory $pageFactory,
        PageRepository $pageRepository
    ) {
        $this->csvReader = $sampleDataContext->getCsvReader();
        $this->readCmsData = $readCmsData;
        $this->moduleDataSetup = $moduleDataSetup;
        $this->cmsPageResourceModel = $cmsPageResourceModel;
        $this->dateTime = $dateTime;
        $this->pageFactory = $pageFactory;
        $this->pageRepository = $pageRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $fixtureFileName = $this->readCmsData->readCmsPageCsv();

        $this->moduleDataSetup->startSetup();

        if (file_exists($fixtureFileName)) {
            $rows = $this->csvReader->getData($fixtureFileName);
            $header = array_shift($rows);

            foreach ($rows as $row) {
                $data = [];
                foreach ($row as $key => $value) {
                    $data[$header[$key]] = $value;
                }

                $pageIdentifier = $data['identifier'];

                if ($this->cmsPageResourceModel->checkIdentifier($pageIdentifier, Store::DEFAULT_STORE_ID) === null) {
                    $model = $this->pageFactory->create();

                    $model->setIdentifier($data['identifier'])
                        ->setTitle($data['title'])
                        ->setPageLayout($data['page_layout'])
                        ->setContentHeading($data['content_heading'])
                        ->setContent($data['content'])
                        ->setUpdateTime($this->dateTime->gmtDate())
                        ->setIsActive($data['is_active'])
                        ->setSortOrder($data['sort_order'])
                        ->setCreationTime($data['creation_time']);
                    $this->pageRepository->save($model);
                } elseif ($this->cmsPageResourceModel->checkIdentifier($pageIdentifier, Store::DEFAULT_STORE_ID) !== null) {
                    $model = $this->pageFactory->create();

                    $oldCmsPage = $model->load($pageIdentifier);
                    $this->cmsPageResourceModel->delete($oldCmsPage);

                    $model->setId($this->cmsPageResourceModel->checkIdentifier($pageIdentifier, Store::DEFAULT_STORE_ID))
                        ->setIdentifier($data['identifier'])
                        ->setTitle($data['title'])
                        ->setPageLayout($data['page_layout'])
                        ->setContentHeading($data['content_heading'])
                        ->setContent($data['content'])
                        ->setCreationTime($data['creation_time'])
                        ->setUpdateTime($this->dateTime->gmtDate())
                        ->setIsActive($data['is_active'])
                        ->setSortOrder($data['sort_order']);
                    $this->pageRepository->save($model);
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
