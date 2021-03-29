<?php
/**
 * Repository Place
 *
 * @category  Smile
 * @package   Smile\Manifestation
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */

namespace Smile\Manifestation\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Smile\Manifestation\Api\PlaceRepositoryInterface;
use Smile\Manifestation\Api\Data\PlaceInterface;
use Smile\Manifestation\Api\Data\PlaceSearchResultsInterfaceFactory;
use Smile\Manifestation\Model\ResourceModel\Place as PlaceResourceModel;
use Smile\Manifestation\Model\ResourceModel\Place\CollectionFactory;

/**
 * Class PlaceRepository
 */
class PlaceRepository implements PlaceRepositoryInterface
{
    /**
     * Place Resource Model
     *
     * @var PlaceResourceModel
     */
    protected $placeResource;

    /**
     * Place Model Factory
     *
     * @var PlaceFactory
     */
    protected $placeFactory;

    /**
     * Place Collection Factory
     *
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * Collection Processor Interface
     *
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * SearchResults Interface
     *
     * @var PlaceSearchResultsInterfaceFactory
     */
    protected $placeSearchResultsFactory;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * PlaceRepository constructor
     *
     * @param PlaceResourceModel $placeResource
     * @param PlaceFactory $placeFactory
     * @param CollectionFactory $collectionFactory
     * @param CollectionProcessorInterface $processor
     * @param PlaceSearchResultsInterfaceFactory $placeSearchResultsFactory
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        PlaceResourceModel $placeResource,
        PlaceFactory $placeFactory,
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        PlaceSearchResultsInterfaceFactory $placeSearchResultsFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->placeResource = $placeResource;
        $this->placeFactory = $placeFactory;
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->placeSearchResultsFactory = $placeSearchResultsFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @inheritdoc
     */
    public function getById($placeId): PlaceInterface
    {
        /** @var Place $place */
        $place = $this->placeFactory->create();
        $this->placeResource->load($place, $placeId);
        if (!$place->getId()) {
            throw new NoSuchEntityException(__('No such manifestation with id %1 !', $placeId));
        }

        return $place;
    }

    /**
     * @inheritdoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria = null): \Smile\Manifestation\Api\Data\PlaceSearchResultsInterface
    {
        /** @var ResourceModel\Place\Collection $collection */
        $collection = $this->collectionFactory->create();

        if (null === $searchCriteria) {
            $searchCriteria = $this->searchCriteriaBuilder->create();
        } else {
            $this->collectionProcessor->process($searchCriteria, $collection);
        }

        /** @var \Smile\Manifestation\Api\Data\PlaceSearchResultsInterface $searchResults */
        $searchResults = $this->placeSearchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * @inheritdoc
     */
    public function delete(PlaceInterface $place)
    {
        try {
            $this->placeResource->delete($place);
        } catch (\Exception $e) {
            throw new StateException(
                __(
                    'Cannot delete place with id %1',
                    $place->getId()
                ),
                $e
            );
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function deleteById($placeId)
    {
        $place = $this->getById($placeId);
        return $this->delete($place);
    }

    /**
     * @inheritdoc
     */
    public function save(PlaceInterface $place)
    {
        try {
            $this->placeResource->save($place);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(
                __(
                    'Could not save place: %1',
                    $e->getMessage()
                ),
                $e
            );
        }
    }
}
