<?php
/**
 * PlaceForm DataProvider
 *
 * @category  Smile
 * @package   Smile\Manifestation
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */
namespace Smile\Manifestation\Ui\DataProvider\Place\Form;

use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Smile\Manifestation\Api\Data\PlaceInterface;
use Smile\Manifestation\Api\PlaceRepositoryInterface;
use Smile\Manifestation\Model\ResourceModel\Place\CollectionFactory;

/**
 * DataProvider for place edit form
 *
 * @api
 * @since 101.0.0
 */
class PlaceDataProvider extends AbstractDataProvider
{
    /**
     * @var PoolInterface
     */
    protected $pool;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var PlaceRepositoryInterface
     */
    protected $placeRepository;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param PoolInterface $pool
     * @param RequestInterface $request
     * @param PlaceRepositoryInterface $placeRepository
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        PoolInterface $pool,
        RequestInterface $request,
        PlaceRepositoryInterface $placeRepository,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->pool = $pool;
        $this->request = $request;
        $this->placeRepository = $placeRepository;
    }

    /**
     * {@inheritdoc}
     * @since 101.0.0
     */
    public function getData()
    {
        $placeId = $this->request->getParam(PlaceInterface::PLACE_ID);

        if ($placeId != null) {
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

        /** @var ModifierInterface $modifier */
        foreach ($this->pool->getModifiersInstances() as $modifier) {
            $this->data = $modifier->modifyData($this->data);
        }

        return $this->data;
    }

    /**
     * {@inheritdoc}
     * @since 101.0.0
     */
    public function getMeta()
    {
        $meta = parent::getMeta();

        /** @var ModifierInterface $modifier */
        foreach ($this->pool->getModifiersInstances() as $modifier) {
            $meta = $modifier->modifyMeta($meta);
        }

        return $meta;
    }
}
