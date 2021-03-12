<?php

namespace Smile\Manifestation\Ui\DataProvider\Manifestation;

use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Smile\Manifestation\Model\ResourceModel\Manifestation\CollectionFactory as ManifestationCollectionFactory;

/**
 * Class DataProvider
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * Loaded Data
     *
     * @var array
     */
    protected $loadedData = [];

    /**
     * Manifestation Collection Factory
     *
     * @var ManifestationCollectionFactory
     */
    protected $manifestationCollectionFactory;

    /**
     * DataProvider constructor
     *
     * @var RequestInterface
     */
    protected $request;

    /**
     * DataProvider constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param ManifestationCollectionFactory $manifestationCollectionFactory
     * @param RequestInterface $request
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        ManifestationCollectionFactory $manifestationCollectionFactory,
        RequestInterface $request,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $manifestationCollectionFactory->create();
        $this->request = $request;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (!empty($this->loadedData)) {
            return $this->loadedData;
        }

        $this->collection->getSelect()
            ->columns('eav_manifestation_link.place_id')
            ->joinLeft(
                ['eav_manifestation_link' => $this->collection->getTable('eav_manifestation_link')],
                'eav_manifestation_link.manifestation_id = main_table.entity_id',
                []
            );

        foreach ($this->getCollection() as $item) {
            $this->loadedData[$item->getId()] = $item->getData();
        }

        return $this->loadedData;
    }
}
