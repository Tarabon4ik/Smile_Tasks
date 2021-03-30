<?php
/**
 * Manifestation Upgrade Data Script
 *
 * @category  Smile
 * @package   Smile\Manifestation
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */

namespace Smile\Manifestation\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Smile\Manifestation\Api\ManifestationRepositoryInterface;
use Smile\Manifestation\Api\PlaceRepositoryInterface;
use Smile\Manifestation\Model\ManifestationFactory;
use Smile\Manifestation\Model\ManifestationPlaceLinkFactory;
use Smile\Manifestation\Model\PlaceFactory;
use Smile\Manifestation\Model\ResourceModel\ManifestationPlaceLink as ManifestationPlaceLinkResourceModel;

/**
 * @codeCoverageIgnore
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * Manifestation Factory
     *
     * @var ManifestationFactory
     */
    protected $manifestationFactory;

    /**
     * Manifestation Repository
     *
     * @var ManifestationRepositoryInterface
     */
    protected $manifestationRepository;

    /**
     * Place Factory
     *
     * @var PlaceFactory
     */
    protected $placeFactory;

    /**
     * Place Repository
     *
     * @var PlaceRepositoryInterface
     */
    protected $placeRepository;

    /**
     * ManifestationPlaceLink Factory
     *
     * @var ManifestationPlaceLinkFactory
     */
    protected $manifestationPlaceLinkFactory;

    /**
     * ManifestationPlaceLink ResourceModel
     *
     * @var ManifestationPlaceLinkResourceModel
     */
    protected $manifestationPlaceLinkResourceModel;

    /**
     * UpgradeData constructor
     *
     * @param ManifestationFactory $manifestationFactory
     * @param ManifestationRepositoryInterface $manifestationRepository
     * @param PlaceFactory $placeFactory
     * @param PlaceRepositoryInterface $placeRepository
     * @param ManifestationPlaceLinkFactory $manifestationPlaceLinkFactory
     * @param ManifestationPlaceLinkResourceModel $manifestationPlaceLinkResourceModel
     */
    public function __construct(
        ManifestationFactory $manifestationFactory,
        ManifestationRepositoryInterface $manifestationRepository,
        PlaceFactory $placeFactory,
        PlaceRepositoryInterface $placeRepository,
        ManifestationPlaceLinkFactory $manifestationPlaceLinkFactory,
        ManifestationPlaceLinkResourceModel $manifestationPlaceLinkResourceModel
    ) {
        $this->manifestationFactory = $manifestationFactory;
        $this->manifestationRepository = $manifestationRepository;
        $this->placeFactory = $placeFactory;
        $this->placeRepository = $placeRepository;
        $this->manifestationPlaceLinkFactory = $manifestationPlaceLinkFactory;
        $this->manifestationPlaceLinkResourceModel = $manifestationPlaceLinkResourceModel;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @throws \Exception
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        /** @var \Smile\Manifestation\Model\Manifestation $manifestation*/
        $manifestation = $this->manifestationFactory->create();
        $manifestation->setTitle('testManifestation');
        $manifestation->setDescription('testDescription');
        $manifestation->setCreatedAt('2021-02-23 00:00:00');
        $manifestation->setUpdatedAt('2021-02-23 00:00:00');
        $manifestation->setStartDate('2021-02-23 00:00:00');
        $manifestation->setEndDate('2021-02-23 00:00:00');
        $manifestation->setIsNeedWater(0);
        $manifestation->setIsNeedElectricity(0);

        $this->manifestationRepository->save($manifestation);

        /** @var \Smile\Manifestation\Model\Place $place*/
        $place = $this->placeFactory->create();
        $place->setName('testPlace');
        $place->setCreatedAt('2021-03-23 00:00:00');
        $place->setUpdatedAt('2021-05-23 00:00:00');
        $place->setDescription('testDescription');
        $place->setEmail('test@gmail.com');

        $this->placeRepository->save($place);

        /** @var \Smile\Manifestation\Model\ManifestationPlaceLink $manifestationPlaceLink */
        $manifestationPlaceLink = $this->manifestationPlaceLinkFactory->create();
        $manifestationPlaceLink->setManifestationId(1);
        $manifestationPlaceLink->setPlaceId(1);
        $manifestationPlaceLink->setPriority(10);

        $this->manifestationPlaceLinkResourceModel->save($manifestationPlaceLink);

        $setup->endSetup();
    }
}
