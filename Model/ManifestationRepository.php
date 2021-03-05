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
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Smile\Manifestation\Api\ManifestationRepositoryInterface;
use Smile\Manifestation\Api\Data\ManifestationInterface;
use Smile\Manifestation\Api\Data\ManifestationInterfaceFactory;
use Smile\Manifestation\Model\ManifestationFactory;
use Smile\Manifestation\Model\ResourceModel\Manifestation as ManifestationResourceModel;
use Smile\Manifestation\Model\ResourceModel\Manifestation\CollectionFactory;

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
     * ManifestationRepository constructor.
     *
     * @param ManifestationResourceModel $manifestationResource
     * @param ManifestationFactory $manifestationFactory
     * @param CollectionFactory $collectionFactory
     * @param CollectionProcessorInterface $processor
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     */
    public function __construct(
        ManifestationResourceModel $manifestationResource,
        ManifestationFactory $manifestationFactory,
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        SearchResultsInterfaceFactory $searchResultsFactory
    ) {
        $this->manifestationResource = $manifestationResource;
        $this->manifestationFactory = $manifestationFactory;
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * @inheritdoc
     */
    public function getById(int $manifestationId)
    {
        /** @var Manifestation $manifestation */
        $manifestation = $this->manifestationFactory->create();
        $this->manifestationResource->load($manifestation, $manifestationId, ManifestationInterface::ID);
        if (!$manifestation->getId()) {
            throw new NoSuchEntityException(__('No such manifestation with id %1 !', $manifestationId));
        }

        return $manifestation;
    }

    /**
     * @inheritdoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var ResourceModel\Manifestation\Collection $collection */
        $collection = $this->collectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var SearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
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
