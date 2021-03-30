<?php
/**
 * Get ManifestationPlaceLinks
 *
 * @category  Smile
 * @package   Smile\Manifestation
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */
declare(strict_types=1);

namespace Smile\Manifestation\Model\ManifestationPlaceLink\Command;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Smile\Manifestation\Model\ResourceModel\ManifestationPlaceLink\Collection as ManifestationPlaceLinkCollection;
use Smile\Manifestation\Model\ResourceModel\ManifestationPlaceLink\CollectionFactory as ManifestationPlaceLinkCollectionFactory;
use Smile\Manifestation\Api\Data\ManifestationPlaceLinkSearchResultsInterface;
use Smile\Manifestation\Api\Data\ManifestationPlaceLinkSearchResultsInterfaceFactory;
use Smile\Manifestation\Api\GetManifestationPlaceLinksInterface;

/**
 * @inheritdoc
 */
class GetManifestationPlaceLinks implements GetManifestationPlaceLinksInterface
{
    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var ManifestationPlaceLinkCollectionFactory
     */
    protected $manifestationPlaceLinkCollectionFactory;

    /**
     * @var ManifestationPlaceLinkSearchResultsInterfaceFactory
     */
    protected $manifestationPlaceLinkSearchResultsFactory;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @param CollectionProcessorInterface $collectionProcessor
     * @param ManifestationPlaceLinkCollectionFactory $manifestationPlaceLinkCollectionFactory
     * @param ManifestationPlaceLinkSearchResultsInterfaceFactory $manifestationPlaceLinkSearchResultsFactory
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        CollectionProcessorInterface $collectionProcessor,
        ManifestationPlaceLinkCollectionFactory $manifestationPlaceLinkCollectionFactory,
        ManifestationPlaceLinkSearchResultsInterfaceFactory $manifestationPlaceLinkSearchResultsFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->collectionProcessor = $collectionProcessor;
        $this->manifestationPlaceLinkCollectionFactory = $manifestationPlaceLinkCollectionFactory;
        $this->manifestationPlaceLinkSearchResultsFactory = $manifestationPlaceLinkSearchResultsFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @inheritdoc
     */
    public function execute(SearchCriteriaInterface $searchCriteria): ManifestationPlaceLinkSearchResultsInterface
    {
        /** @var ManifestationPlaceLinkCollection $collection */
        $collection = $this->manifestationPlaceLinkCollectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var ManifestationPlaceLinkSearchResultsInterface $searchResult */
        $searchResult = $this->manifestationPlaceLinkSearchResultsFactory->create();

        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());
        $searchResult->setSearchCriteria($searchCriteria);

        return $searchResult;
    }
}
