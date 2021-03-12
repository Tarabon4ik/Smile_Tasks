<?php

declare(strict_types=1);

namespace Smile\Manifestation\Model\Manifestation;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Smile\Manifestation\Api\Data\ManifestationPlaceLinkInterfaceFactory;
use Smile\Manifestation\Api\Data\ManifestationPlaceLinkInterface;
use Smile\Manifestation\Api\GetManifestationPlaceLinksInterface;
use Smile\Manifestation\Api\ManifestationPlaceLinksDeleteInterface;
use Smile\Manifestation\Api\ManifestationPlaceLinksSaveInterface;

/**
 * At the time of processing Manifestation save form this class used to save links correctly
 * Performs replace strategy of sources for the Manifestation
 */
class ManifestationPlaceLinkProcessor
{
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var ManifestationPlaceLinkInterfaceFactory
     */
    private $manifestationPlaceLinkFactory;

    /**
     * @var ManifestationPlaceLinksSaveInterface
     */
    private $manifestationPlaceLinksSave;

    /**
     * @var ManifestationPlaceLinksDeleteInterface
     */
    private $manifestationPlaceLinksDelete;

    /**
     * @var GetManifestationPlaceLinksInterface
     */
    private $getManifestationPlaceLinks;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param ManifestationPlaceLinkInterfaceFactory $manifestationPlaceLinkFactory
     * @param ManifestationPlaceLinksSaveInterface $manifestationPlaceLinksSave
     * @param ManifestationPlaceLinksDeleteInterface $manifestationPlaceLinksDelete
     * @param GetManifestationPlaceLinksInterface $getManifestationPlaceLinks
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ManifestationPlaceLinkInterfaceFactory $manifestationPlaceLinkFactory,
        ManifestationPlaceLinksSaveInterface $manifestationPlaceLinksSave,
        ManifestationPlaceLinksDeleteInterface $manifestationPlaceLinksDelete,
        GetManifestationPlaceLinksInterface $getManifestationPlaceLinks,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->manifestationPlaceLinkFactory = $manifestationPlaceLinkFactory;
        $this->manifestationPlaceLinksSave = $manifestationPlaceLinksSave;
        $this->manifestationPlaceLinksDelete = $manifestationPlaceLinksDelete;
        $this->getManifestationPlaceLinks = $getManifestationPlaceLinks;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * @param int $manifestationId
     * @param array $linksData
     * @return void
     * @throws InputException
     */
    public function process(int $manifestationId, array $linksData)
    {
        $linksForDelete = $this->getAssignedLinks($manifestationId);
        $linksForSave = [];

        foreach ($linksData as $linkData) {
            $placeId = $linkData[ManifestationPlaceLinkInterface::PLACE_ID];

            if (isset($linksForDelete[$placeId])) {
                $link = $linksForDelete[$placeId];
            } else {
                /** @var ManifestationPlaceLinkInterface $link */
                $link = $this->manifestationPlaceLinkFactory->create();
            }

            $linkData[ManifestationPlaceLinkInterface::MANIFESTATION_ID] = $manifestationId;
            $this->dataObjectHelper->populateWithArray($link, $linkData, ManifestationPlaceLinkInterface::class);

            $linksForSave[] = $link;
            unset($linksForDelete[$placeId]);
        }

        if (count($linksForSave) > 0) {
            $this->manifestationPlaceLinksSave->execute($linksForSave);
        }
        if (count($linksForDelete) > 0) {
            $this->manifestationPlaceLinksDelete->execute($linksForDelete);
        }
    }

    /**
     * Retrieves links that are assigned to $stockId
     *
     * @param int $manifestationId
     * @return ManifestationPlaceLinkInterface[]
     */
    private function getAssignedLinks(int $manifestationId): array
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(ManifestationPlaceLinkInterface::MANIFESTATION_ID, $manifestationId)
            ->create();

        $result = [];
        foreach ($this->getManifestationPlaceLinks->execute($searchCriteria)->getItems() as $link) {
            $result[$link->getPlaceId()] = $link;
        }
        return $result;
    }
}
