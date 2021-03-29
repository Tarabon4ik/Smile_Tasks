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
use Smile\Manifestation\Api\ManifestationPlaceLinkRepositoryInterface;
use Smile\Manifestation\Api\Data\ManifestationPlaceLinkInterface;
use Smile\Manifestation\Api\Data\ManifestationPlaceLinkSearchResultsInterfaceFactory;
use Smile\Manifestation\Model\ResourceModel\ManifestationPlaceLink as ManifestationPlaceLinkResourceModel;
use Smile\Manifestation\Model\ResourceModel\ManifestationPlaceLink\CollectionFactory;

/**
 * Class PlaceRepository
 */
class ManifestationPlaceLinkRepository implements ManifestationPlaceLinkRepositoryInterface
{
    /**
     * ManifestationPlaceLink Resource Model
     *
     * @var ManifestationPlaceLinkResourceModel
     */
    protected $manifestationPlaceLinkResource;

    /**
     * ManifestationPlaceLink Factory
     *
     * @var ManifestationPlaceLinkFactory
     */
    protected $manifestationPlaceLinkFactory;

    /**
     * ManifestationPlaceLink Collection Factory
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
     * @var ManifestationPlaceLinkSearchResultsInterfaceFactory
     */
    protected $manifestationPlaceLinkSearchResultsFactory;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * PlaceRepository constructor
     *
     * @param ManifestationPlaceLinkResourceModel $manifestationPlaceLinkResource
     * @param ManifestationPlaceLinkFactory $manifestationPlaceLinkFactory
     * @param CollectionFactory $collectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param ManifestationPlaceLinkSearchResultsInterfaceFactory $manifestationPlaceLinkSearchResultsFactory
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        ManifestationPlaceLinkResourceModel $manifestationPlaceLinkResource,
        ManifestationPlaceLinkFactory $manifestationPlaceLinkFactory,
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        ManifestationPlaceLinkSearchResultsInterfaceFactory $manifestationPlaceLinkSearchResultsFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->manifestationPlaceLinkResource = $manifestationPlaceLinkResource;
        $this->manifestationPlaceLinkFactory = $manifestationPlaceLinkFactory;
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->manifestationPlaceLinkSearchResultsFactory = $manifestationPlaceLinkSearchResultsFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @inheritdoc
     */
    public function getById($linkId): ManifestationPlaceLinkInterface
    {
        /** @var ManifestationPlaceLink $link */
        $link = $this->manifestationPlaceLinkFactory->create();
        $this->manifestationPlaceLinkResource->load($link, $linkId, ManifestationPlaceLinkInterface::LINK_ID);
        if (!$link->getId()) {
            throw new NoSuchEntityException(__('No such link with id %1 !', $linkId));
        }

        return $link;
    }

    /**
     * @inheritdoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria = null): \Smile\Manifestation\Api\Data\ManifestationPlaceLinkSearchResultsInterface
    {
        /** @var ResourceModel\ManifestationPlaceLink\Collection $collection */
        $collection = $this->collectionFactory->create();

        if (null === $searchCriteria) {
            $searchCriteria = $this->searchCriteriaBuilder->create();
        } else {
            $this->collectionProcessor->process($searchCriteria, $collection);
        }

        /** @var \Smile\Manifestation\Api\Data\ManifestationPlaceLinkSearchResultsInterface $searchResults */
        $searchResults = $this->manifestationPlaceLinkSearchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * @inheritdoc
     */
    public function delete(ManifestationPlaceLinkInterface $link)
    {
        try {
            $this->manifestationPlaceLinkResource->delete($link);
        } catch (\Exception $e) {
            throw new StateException(
                __(
                    'Cannot delete link with id %1',
                    $link->getLinkId()
                ),
                $e
            );
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function deleteById($linkId)
    {
        $link = $this->getById($linkId);
        return $this->delete($link);
    }

    /**
     * @inheritdoc
     */
    public function save(ManifestationPlaceLinkInterface $link)
    {
        try {
            $this->manifestationPlaceLinkResource->save($link);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(
                __(
                    'Could not save link: %1',
                    $e->getMessage()
                ),
                $e
            );
        }
    }
}
