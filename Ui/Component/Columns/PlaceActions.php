<?php
/**
 * PlaceActions
 *
 * @category  Smile
 * @package   Smile\Manifestation
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */
namespace Smile\Manifestation\Ui\Component\Columns;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Smile\Manifestation\Model\Place;

/**
 * Class Manifestation
 */
class PlaceActions extends Column
{
    /**
     * Url Interface
     *
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * PlaceActions constructor
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {

            foreach ($dataSource['data']['items'] as &$item) {
                $item[$this->getData('name')]
                ['edit'] = [
                    'href' => $this->urlBuilder->getUrl(
                        $this->getData('config/editUrlPath'),
                        [Place::PLACE_ID => $item[Place::PLACE_ID]]
                    ),
                    'label' => __('Edit')
                ];
            }
        }

        return $dataSource;
    }
}
