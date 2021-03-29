<?php

declare(strict_types=1);

namespace Smile\Manifestation\Ui\DataProvider;

use Magento\Backend\Model\Session;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider;
use Magento\Ui\DataProvider\SearchResultFactory;
use Magento\Framework\App\ObjectManager;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Smile\Manifestation\Api\Data\PlaceInterface;
use Smile\Manifestation\Api\PlaceRepositoryInterface;

/**
 * Data provider for admin place grid.
 *
 * @api
 */
class PlaceDataProvider extends DataProvider
{
    /**
     * @var PlaceRepositoryInterface
     */
    private $placeRepository;

    /**
     * @var SearchResultFactory
     */
    private $searchResultFactory;

    /**
     * @var Session
     */
    private $session;

    /**
     * Total place count
     *
     * @var int
     */
    private $placeCount;

    /**
     * @var PoolInterface
     */
    private $pool;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param ReportingInterface $reporting
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param RequestInterface $request
     * @param FilterBuilder $filterBuilder
     * @param PlaceRepositoryInterface $placeRepository
     * @param SearchResultFactory $searchResultFactory
     * @param Session $session
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
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        PlaceRepositoryInterface $placeRepository,
        SearchResultFactory $searchResultFactory,
        Session $session,
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
        $this->placeRepository = $placeRepository;
        $this->searchResultFactory = $searchResultFactory;
        $this->session = $session;
        $this->pool = $pool ?: ObjectManager::getInstance()->get(PoolInterface::class);
    }

    /**
     * @inheritdoc
     */
    public function getData()
    {
        $data = parent::getData();

        $placeId = $this->request->getParam(PlaceInterface::PLACE_ID);
        if ($placeId != null) {
            if ($data['totalRecords'] > 0) {
                $place = $this->placeRepository->getById($placeId);

                $dataForSingle[$placeId] = [
                    'general' => [
                        PlaceInterface::PLACE_ID => $place->getId(),
                        PlaceInterface::NAME => $place->getName(),
                        PlaceInterface::ENABLED => $place->isEnabled(),
                        PlaceInterface::DESCRIPTION => $place->getDescription(),
                        PlaceInterface::IMAGE => $place->getImage()
                    ],
                    'contact_info' => [
                        PlaceInterface::CONTACT_NAME => $place->getContactName(),
                        PlaceInterface::EMAIL => $place->getEmail(),
                        PlaceInterface::PHONE => $place->getPhone()
                    ],
                    'address' => [
                        PlaceInterface::LATITUDE => $place->getLatitude(),
                        PlaceInterface::LONGITUDE => $place->getLongitude(),
                        PlaceInterface::COUNTRY_ID => $place->getCountryId(),
                        PlaceInterface::REGION_ID => $place->getRegionId(),
                        PlaceInterface::REGION => $place->getRegion(),
                        PlaceInterface::CITY => $place->getCity(),
                        PlaceInterface::STREET => $place->getStreet(),
                    ],
                ];
                return $dataForSingle;
            }
        }
        $data['totalRecords'] = $this->getPlacesCount();

        /** @var ModifierInterface $modifier */
        foreach ($this->pool->getModifiersInstances() as $modifier) {
            $data = $modifier->modifyData($data);
        }

        return $data;
    }

    /**
     * Get total places count, without filter be place name.
     *
     * Get total places count, without filter in order to ui/grid/columns/multiselect::updateState()
     * works correctly with places selection.
     *
     * @return int
     */
    private function getPlacesCount()
    {
        if (!$this->placeCount) {
            $this->placeCount = $this->placeRepository->getList()->getTotalCount();
        }

        return $this->placeCount;
    }
}
