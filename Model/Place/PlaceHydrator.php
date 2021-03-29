<?php

declare(strict_types=1);

namespace Smile\Manifestation\Model\Place;

use Magento\Framework\Api\DataObjectHelper;
use Smile\Manifestation\Api\Data\PlaceInterface;

/**
 * Populate Source by data. Specified for form structure
 *
 * @api
 */
class PlaceHydrator
{
    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var PlaceRegionDataProcessor
     */
    private $placeRegionDataProcessor;

    /**
     * @var PlaceCoordinatesDataProcessor
     */
    private $placeCoordinatesDataProcessor;

    /**
     * @param DataObjectHelper $dataObjectHelper
     * @param PlaceRegionDataProcessor $placeRegionDataProcessor
     * @param PlaceCoordinatesDataProcessor $placeCoordinatesDataProcessor
     */
    public function __construct(
        DataObjectHelper $dataObjectHelper,
        PlaceRegionDataProcessor $placeRegionDataProcessor,
        PlaceCoordinatesDataProcessor $placeCoordinatesDataProcessor
    ) {
        $this->dataObjectHelper = $dataObjectHelper;
        $this->placeRegionDataProcessor = $placeRegionDataProcessor;
        $this->placeCoordinatesDataProcessor = $placeCoordinatesDataProcessor;
    }

    /**
     * @param PlaceInterface $place
     * @param array $data
     *
     * @return PlaceInterface
     */
    public function hydrate(PlaceInterface $place, array $data): PlaceInterface
    {
        $data['general'] = $this->placeRegionDataProcessor->execute($data['general']);
        $data['general'] = $this->placeCoordinatesDataProcessor->execute($data['general']);

        $this->dataObjectHelper->populateWithArray($place, $data['general'], PlaceInterface::class);

        return $place;
    }
}
