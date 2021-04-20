<?php

namespace Smile\Onepage\Block\Manifestation;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Smile\Manifestation\Api\PlaceAttributeRepositoryInterface as PlaceAttributeRepository;
use Smile\Manifestation\Model\ResourceModel\Place\CollectionFactory as PlaceCollectionFactory;
use Smile\Onepage\Ui\Component\Form\AttributeMapper;

/**
 * Class LayoutProcessor
 */
class LayoutProcessor implements LayoutProcessorInterface
{
    /**
     * @var PlaceCollectionFactory
     */
    protected $attrCollectionFactory;

    /**
     * @var AttributeMapper
     */
    protected $attributeMapper;

    /**
     * @var AttributeMerger
     */
    protected $merger;

    /**
     * @var PlaceAttributeRepository
     */
    protected $placeAttributeRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * Initialize data provider with data source
     *
     * @param PlaceCollectionFactory $attrCollectionFactory
     * @param AttributeMapper $attributeMapper
     * @param AttributeMerger $merger
     * @param PlaceAttributeRepository $placeAttributeRepository
     */
    public function __construct(
        PlaceCollectionFactory $attrCollectionFactory,
        AttributeMapper $attributeMapper,
        AttributeMerger $merger,
        PlaceAttributeRepository $placeAttributeRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->attrCollectionFactory = $attrCollectionFactory;
        $this->attributeMapper = $attributeMapper;
        $this->merger = $merger;
        $this->placeAttributeRepository = $placeAttributeRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * Process js Layout of block
     *
     * @param array $jsLayout
     * @return array
     */
    public function process($jsLayout)
    {
        $elements = $this->getPlaceAttributes();

        $fields = &$jsLayout['components']['manifestation']['children']['steps']['children']['place-step']
        ['children']['place']['children']['place-fieldset']['children'];

        $fieldCodes = array_keys($fields);
        $elements = array_filter($elements, function ($key) use ($fieldCodes) {
            return in_array($key, $fieldCodes);
        }, ARRAY_FILTER_USE_KEY);

        $fields = $this->merger->merge(
            $elements,
            'manifestationProvider',
            'place',
            $fields
        );

        return $jsLayout;
    }

    /**
     * Process js Layout of block
     *
     * @return array
     * @throws LocalizedException
     */
    public function getPlaceAttributes(): array
    {
        $searchCriteria = $this->searchCriteriaBuilder->create();

        $attributes = $this->placeAttributeRepository->getList($searchCriteria)->getItems();

        $elements = [];
        /** @var \Magento\Eav\Api\Data\AttributeInterface $attribute */
        foreach ($attributes as $attribute) {
            $code = $attribute->getAttributeCode();

            $elements[$code] = $this->attributeMapper->map($attribute);
            if (isset($elements[$code]['label'])) {
                $label = $elements[$code]['label'];
                $elements[$code]['label'] = __($label);
            }
        }

        return $elements;
    }
}
