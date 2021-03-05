<?php
/**
 * Repository ManifestationLink
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
use Smile\Manifestation\Api\ManifestationLinkRepositoryInterface;
use Smile\Manifestation\Api\Data\ManifestationLinkInterface;
use Smile\Manifestation\Api\Data\ManifestationLinkInterfaceFactory;
use Smile\Manifestation\Model\ManifestationLinkFactory;
use Smile\Manifestation\Model\ResourceModel\ManifestationLink as ManifestationLinkResourceModel;
use Smile\Manifestation\Model\ResourceModel\ManifestationLink\CollectionFactory;

/**
 * Class ManifestationLinkRepository
 */
class ManifestationLinkRepository implements ManifestationLinkRepositoryInterface
{
    /**
     * ManifestationLink Resource Model
     *
     * @var ManifestationLinkResourceModel
     */
    protected $manifestationLinkResource;

    /**
     * ManifestationLink Model Factory
     *
     * @var ManifestationLinkFactory
     */
    protected $manifestationLinkFactory;

    /**
     * ManifestationLink Collection Factory
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
     * ManifestationLinkRepository constructor
     *
     * @param ManifestationLinkResourceModel $manifestationLinkResource
     * @param ManifestationLinkFactory $manifestationLinkFactory
     * @param CollectionFactory $collectionFactory
     * @param CollectionProcessorInterface $processor
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     */
    public function __construct(
        ManifestationLinkResourceModel $manifestationLinkResource,
        ManifestationLinkFactory $manifestationLinkFactory,
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        SearchResultsInterfaceFactory $searchResultsFactory
    ) {
        $this->manifestationLinkResource = $manifestationLinkResource;
        $this->manifestationLinkFactory = $manifestationLinkFactory;
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * @inheritdoc
     */
    public function getById(int $manifestationLinkId)
    {
        /** @var ManifestationLink $manifestationLink */
        $manifestationLink = $this->manifestationLinkFactory->create();
        $this->manifestationLinkResource->load($manifestationLink, $manifestationLinkId, ManifestationLinkInterface::ID);
        if (!$manifestationLink->getId()) {
            throw new NoSuchEntityException(__('No such manifestation link with id %1 !', $manifestationLinkId));
        }

        return $manifestationLink;
    }

    /**
     * @inheritdoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var ResourceModel\ManifestationLink\Collection $collection */
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
    public function delete(ManifestationLinkInterface $manifestationLink)
    {
        try {
            $this->manifestationLinkResource->delete($manifestationLink);
        } catch (\Exception $e) {
            throw new StateException(
                __(
                    'Cannot delete manifestation link with id %1',
                    $manifestationLink->getId()
                ),
                $e
            );
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function deleteById($manifestationLinkId)
    {
        $manifestationLink = $this->getById($manifestationLinkId);
        return $this->delete($manifestationLink);
    }

    /**
     * @inheritdoc
     */
    public function save(ManifestationLinkInterface $manifestationLink)
    {
        try {
            $this->manifestationLinkResource->save($manifestationLink);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(
                __(
                    'Could not save manifestation link: %1',
                    $e->getMessage()
                ),
                $e
            );
        }
        return $manifestationLink;
    }
}
