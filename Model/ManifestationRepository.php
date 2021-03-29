<?php
/**
 * Repository Manifestation
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
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Smile\Manifestation\Api\Data\ManifestationInterface;
use Smile\Manifestation\Api\Data\ManifestationSearchResultsInterfaceFactory;
use Smile\Manifestation\Model\ResourceModel\Manifestation as ManifestationResourceModel;
use Smile\Manifestation\Model\ResourceModel\Manifestation\CollectionFactory;
use Smile\Manifestation\Api\ManifestationRepositoryInterface;

/**
 * Class ManifestationRepository
 */
class ManifestationRepository implements ManifestationRepositoryInterface
{
    /**
     * Manifestation Resource Model
     *
     * @var ManifestationResourceModel
     */
    protected $manifestationResource;

    /**
     * Manifestation Model Factory
     *
     * @var ManifestationFactory
     */
    protected $manifestationFactory;

    /**
     * Manifestation Collection Factory
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
     * @var SearchResultsInterface
     */
    protected $searchResultsFactory;

    /**
     * SearchResults Interface
     *
     * @var ManifestationSearchResultsInterfaceFactory
     */
    protected $manifestationSearchResultsFactory;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * ManifestationRepository constructor.
     *
     * @param ManifestationResourceModel $manifestationResource
     * @param ManifestationFactory $manifestationFactory
     * @param CollectionFactory $collectionFactory
     * @param CollectionProcessorInterface $processor
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     * @param ManifestationSearchResultsInterfaceFactory $manifestationSearchResultsFactory
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        ManifestationResourceModel $manifestationResource,
        ManifestationFactory $manifestationFactory,
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        SearchResultsInterfaceFactory $searchResultsFactory,
        ManifestationSearchResultsInterfaceFactory $manifestationSearchResultsFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->manifestationResource = $manifestationResource;
        $this->manifestationFactory = $manifestationFactory;
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->manifestationSearchResultsFactory = $manifestationSearchResultsFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @inheritdoc
     */
    public function getById($manifestationId)
    {
        /** @var Manifestation $manifestation */
        $manifestation = $this->manifestationFactory->create();
        $this->manifestationResource->load($manifestation, $manifestationId);
        if (!$manifestation->getId()) {
            throw new NoSuchEntityException(__('No such manifestation with id %1 !', $manifestationId));
        }

        return $manifestation;
    }

    /**
     * @inheritdoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria = null): \Smile\Manifestation\Api\Data\ManifestationSearchResultsInterface
    {
        /** @var ResourceModel\Manifestation\Collection $collection */
        $collection = $this->collectionFactory->create();

        if (null === $searchCriteria) {
            $searchCriteria = $this->searchCriteriaBuilder->create();
        } else {
            $this->collectionProcessor->process($searchCriteria, $collection);
        }

        /** @var \Smile\Manifestation\Api\Data\ManifestationSearchResultsInterface $searchResults */
        $searchResult = $this->manifestationSearchResultsFactory->create();
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());
        $searchResult->setSearchCriteria($searchCriteria);

        return $searchResult;
    }

    /**
     * @inheritdoc
     */
    public function delete(ManifestationInterface $manifestation)
    {
        try {
            $this->manifestationResource->delete($manifestation);
        } catch (\Exception $e) {
            throw new StateException(
                __(
                    'Cannot delete manifestation with id %1',
                    $manifestation->getId()
                ),
                $e
            );
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function deleteById($manifestationId)
    {
        $manifestation = $this->getById($manifestationId);
        return $this->delete($manifestation);
    }

    /**
     * @inheritdoc
     */
    public function save(ManifestationInterface $manifestation)
    {
        try {
            $this->manifestationResource->save($manifestation);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(
                __(
                    'Could not save manifestation: %1',
                    $e->getMessage()
                ),
                $e
            );
        }
        return $manifestation;
    }
}
