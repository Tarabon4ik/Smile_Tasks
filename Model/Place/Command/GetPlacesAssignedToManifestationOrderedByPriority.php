<?php

declare(strict_types=1);

namespace Smile\Manifestation\Model\Place\Command;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Exception\LocalizedException;
use Smile\Manifestation\Api\GetPlacesAssignedToManifestationOrderedByPriorityInterface;
use Smile\Manifestation\Api\GetManifestationPlaceLinksInterface;
use Smile\Manifestation\Api\PlaceRepositoryInterface;
use Smile\Manifestation\Api\Data\ManifestationPlaceLinkInterface;
use Psr\Log\LoggerInterface;

/**
 * @inheritdoc
 */
class GetPlacesAssignedToManifestationOrderedByPriority implements GetPlacesAssignedToManifestationOrderedByPriorityInterface
{
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var PlaceRepositoryInterface
     */
    private $placeRepository;

    /**
     * @var GetManifestationPlaceLinksInterface
     */
    private $getManifestationPlaceLinks;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var SortOrderBuilder
     */
    private $sortOrderBuilder;

    /**
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param PlaceRepositoryInterface $placeRepository
     * @param GetManifestationPlaceLinksInterface $getManifestationPlaceLinks
     * @param SortOrderBuilder $sortOrderBuilder
     * @param LoggerInterface $logger
     */
    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        PlaceRepositoryInterface $placeRepository,
        GetManifestationPlaceLinksInterface $getManifestationPlaceLinks,
        SortOrderBuilder $sortOrderBuilder,
        LoggerInterface $logger
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->placeRepository = $placeRepository;
        $this->getManifestationPlaceLinks = $getManifestationPlaceLinks;
        $this->logger = $logger;
        $this->sortOrderBuilder = $sortOrderBuilder;
    }

    /**
     * @inheritdoc
     */
    public function execute(int $manifestationId): array
    {
        try {
            $stockSourceLinks = $this->getManifestationPlaceLinks($manifestationId);
            $places = [];
            /** @var \Smile\Manifestation\Model\ManifestationPlaceLink $link */
            foreach ($stockSourceLinks as $link) {
                $places[] = $this->placeRepository->getById($link->getPlaceId());
            }

            return $places;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            throw new LocalizedException(__('Could not load Places for Manifestation'), $e);
        }
    }

    /**
     * Get all M\manifestation-place links by given manifestationId
     *
     * @param int $manifestationId
     * @return array
     */
    private function getManifestationPlaceLinks(int $manifestationId): array
    {
        $sortOrder = $this->sortOrderBuilder
            ->setField(ManifestationPlaceLinkInterface::PRIORITY)
            ->setAscendingDirection()
            ->create();
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(ManifestationPlaceLinkInterface::MANIFESTATION_ID, $manifestationId)
            ->addSortOrder($sortOrder)
            ->create();
        $searchResult = $this->getManifestationPlaceLinks->execute($searchCriteria);

        return $searchResult->getItems();
    }
}
