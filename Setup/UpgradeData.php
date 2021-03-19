<?php

namespace Smile\Manifestation\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class UpgradeData implements UpgradeDataInterface
{

    protected $manifestationFactory;

    protected $manifestationRepository;

    protected $placeFactory;

    protected $placeRepository;

    protected $manifestationPlaceLinkFactory;

    protected $manifestationPlaceLinkResourceModel;


    public function __construct(
        \Smile\Manifestation\Model\ManifestationFactory $manifestationFactory,
        \Smile\Manifestation\Api\ManifestationRepositoryInterface $manifestationRepository,
        \Smile\Manifestation\Model\PlaceFactory $placeFactory,
        \Smile\Manifestation\Api\PlaceRepositoryInterface $placeRepository,
        \Smile\Manifestation\Model\ManifestationPlaceLinkFactory $manifestationPlaceLinkFactory,
        \Smile\Manifestation\Model\ResourceModel\ManifestationPlaceLink $manifestationPlaceLinkResourceModel
    )
    {
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
        $manifestation->setTitle('testManifestation')
            ->setDescription('testDescription')
            ->setCreatedAt('2021-02-23 00:00:00')
            ->setUpdatedAt('2021-02-23 00:00:00')
            ->setStartDate('2021-02-23 00:00:00')
            ->setEndDate('2021-02-23 00:00:00')
            ->setIsNeedWater(0)
            ->setIsNeedElectricity(0);

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
        $manifestationPlaceLink->setPriority(1);

        $this->manifestationPlaceLinkResourceModel->save($manifestationPlaceLink);

        $setup->endSetup();
    }
}
