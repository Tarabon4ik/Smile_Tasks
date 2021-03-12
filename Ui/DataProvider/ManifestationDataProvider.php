<?php

declare(strict_types=1);

namespace Smile\Manifestation\Ui\DataProvider;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder as SearchSearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider;
use Magento\Ui\DataProvider\SearchResultFactory;
use Magento\Framework\App\ObjectManager;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Smile\Manifestation\Api\ManifestationRepositoryInterface;
use Smile\Manifestation\Api\GetManifestationPlaceLinksInterface;
use Smile\Manifestation\Api\PlaceRepositoryInterface;
use Smile\Manifestation\Api\GetPlacesAssignedToManifestationOrderedByPriorityInterface;
use Smile\Manifestation\Api\Data\ManifestationPlaceLinkInterface;
use Smile\Manifestation\Api\Data\ManifestationInterface;
use Smile\Manifestation\Api\Data\PlaceInterface;

/**
 * Provider of data to manifestation
 *
 * @api
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ManifestationDataProvider extends DataProvider
{
    /**
     * @var ManifestationRepositoryInterface
     */
    protected $manifestationRepository;

    /**
     * @var SearchResultFactory
     */
    protected $searchResultFactory;

    /**
     * @var GetManifestationPlaceLinksInterface
     */
    protected $getManifestationPlaceLinks;

    /**
     * @var PlaceRepositoryInterface
     */
    protected $placeRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $apiSearchCriteriaBuilder;

    /**
     * @var SortOrderBuilder
     */
    protected $sortOrderBuilder;

    /**
     * @var GetPlacesAssignedToManifestationOrderedByPriorityInterface
     */
    protected $getPlacesAssignedToManifestationOrderedByPriority;

    /**
     * @var PoolInterface
     */
    protected $pool;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param ReportingInterface $reporting
     * @param SearchSearchCriteriaBuilder $searchCriteriaBuilder
     * @param RequestInterface $request
     * @param FilterBuilder $filterBuilder
     * @param ManifestationRepositoryInterface $manifestationRepository
     * @param SearchResultFactory $searchResultFactory
     * @param GetManifestationPlaceLinksInterface $getManifestationPlaceLinks
     * @param PlaceRepositoryInterface $placeRepository
     * @param SearchCriteriaBuilder $apiSearchCriteriaBuilder
     * @param SortOrderBuilder $sortOrderBuilder
     * @param GetPlacesAssignedToManifestationOrderedByPriorityInterface $getPlacesAssignedToManifestationOrderedByPriority
     * @param array $meta
     * @param array $data
     * @param PoolInterface|null $pool
     * @SuppressWarnings(PHPMD.ExcessiveParameterList) All parameters are needed for backward compatibility
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        ReportingInterface $reporting,
        SearchSearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        ManifestationRepositoryInterface $manifestationRepository,
        SearchResultFactory $searchResultFactory,
        GetManifestationPlaceLinksInterface $getManifestationPlaceLinks,
        PlaceRepositoryInterface $placeRepository,
        SearchCriteriaBuilder $apiSearchCriteriaBuilder,
        SortOrderBuilder $sortOrderBuilder,
        GetPlacesAssignedToManifestationOrderedByPriorityInterface $getPlacesAssignedToManifestationOrderedByPriority,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $meta,
            $data
        );
        $this->manifestationRepository = $manifestationRepository;
        $this->searchResultFactory = $searchResultFactory;
        $this->getManifestationPlaceLinks = $getManifestationPlaceLinks;
        $this->placeRepository = $placeRepository;
        $this->apiSearchCriteriaBuilder = $apiSearchCriteriaBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->getPlacesAssignedToManifestationOrderedByPriority = $getPlacesAssignedToManifestationOrderedByPriority;
        $this->pool = $pool ?: ObjectManager::getInstance()->get(PoolInterface::class);
    }

    /**
     * @inheritdoc
     */
    public function getData()
    {
        $data = parent::getData();
        if ('manifestation_form_data_source' === $this->name) {
            // It is need for support of several fieldsets.
            // For details see \Magento\Ui\Component\Form::getDataSourceData
            if ($data['totalRecords'] > 0) {
                $manifestationId = (int)$data['items'][0][ManifestationInterface::ID];
                $manifestationGeneralData = $data['items'][0];
                $dataForSingle[$manifestationId] = [
                    'general' => $manifestationGeneralData,
                    'places' => [
                        'assigned_places' => $this->getAssignedPlacesData($manifestationId),
                    ],
                ];
                $data = $dataForSingle;
            } else {
                $data = [];
            }
        } elseif ('manifestation_listing_data' === $this->name) {
            if ($data['totalRecords'] > 0) {
                foreach ($data['items'] as $index => $manifestation) {
                    $data['items'][$index]['assigned_places'] = $this->getAssignedPlacesById($manifestation['entity_id']);
                }
            }
        }

        /** @var ModifierInterface $modifier */
        foreach ($this->pool->getModifiersInstances() as $modifier) {
            $data = $modifier->modifyData($data);
        }

        return $data;
    }

    /**
     * @inheritdoc
     */
    public function getSearchResult()
    {
        $searchCriteria = $this->getSearchCriteria();
        $result = $this->manifestationRepository->getList($searchCriteria);

        return $this->searchResultFactory->create(
            $result->getItems(),
            $result->getTotalCount(),
            $searchCriteria,
            ManifestationInterface::ID
        );
    }

    /**
     * Returns assigned sources Data
     *
     * @param int $manifestationId
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getAssignedPlacesData(int $manifestationId): array
    {
        $sortOrder = $this->sortOrderBuilder
            ->setField(ManifestationPlaceLinkInterface::PRIORITY)
            ->setAscendingDirection()
            ->create();
        $searchCriteria = $this->apiSearchCriteriaBuilder
            ->addFilter(ManifestationPlaceLinkInterface::MANIFESTATION_ID, $manifestationId)
            ->addSortOrder($sortOrder)
            ->create();

        $searchResult = $this->getManifestationPlaceLinks->execute($searchCriteria);

        if ($searchResult->getTotalCount() === 0) {
            return [];
        }

        $assignedPlacesData = [];
        foreach ($searchResult->getItems() as $link) {
            $place = $this->placeRepository->getById($link->getPlaceId());

            $assignedPlacesData[] = [
                PlaceInterface::NAME => $place->getName(),
                ManifestationPlaceLinkInterface::PLACE_ID => $link->getPlaceId(),
                ManifestationPlaceLinkInterface::MANIFESTATION_ID => $link->getManifestationId(),
                ManifestationPlaceLinkInterface::PRIORITY => $link->getPriority(),
            ];
        }
        return $assignedPlacesData;
    }

    /**
     * Return assigned sources by id
     *
     * @param int $manifestationId
     * @return array
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getAssignedPlacesById(int $manifestationId): array
    {
        $places = $this->getPlacesAssignedToManifestationOrderedByPriority->execute($manifestationId);
        $placesData = [];
        /** @var \Smile\Manifestation\Model\Place $place */
        foreach ($places as $place) {
            $placesData[] = [
                'placeId' => $place->getId(),
                'name' => $place->getName()
            ];
        }

        return $placesData;
    }
}
