<?php
/**
 * Block ListCategoryProducts
 *
 * @category  Smile
 * @package   Smile\Catalog
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */

namespace Smile\Catalog\Block\Category;

use Magento\Catalog\Model\CategoryFactory;
use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Template;
use Magento\Catalog\Block\Product\ListProduct;

/**
 * Class ListCategoryProducts
 */
class ListCategoryProducts extends Template
{
    /**
     * Layout Processors
     *
     * @var array|LayoutProcessorInterface[]
     */
    protected $layoutProcessors;

    /**
     * Category Factory
     *
     * @var CategoryFactory
     */
    protected $categoryFactory;

    /**
     * Json Helper
     *
     * @var Json
     */
    protected $jsonHelper;

    /**
     * @var ListProduct
     */
    protected $listProduct;

    /**
     * ListCategoryProducts constructor
     *
     * @param Template\Context $context
     * @param Json $jsonHelper,
     * @param CategoryFactory $categoryFactory
     * @param array $layoutProcessors
     * @param array $data
     * @param ListProduct $listProduct
     */
    public function __construct(
        Template\Context $context,
        Json $jsonHelper,
        CategoryFactory $categoryFactory,
        ListProduct $listProduct,
        array $layoutProcessors = [],
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->categoryFactory = $categoryFactory;
        $this->jsonHelper = $jsonHelper;
        $this->jsLayout = isset($data['jsLayout']) && is_array($data['jsLayout']) ? $data['jsLayout'] : [];
        $this->layoutProcessors = $layoutProcessors;
        $this->listProduct = $listProduct;
    }

    /**
     * Get Js Layout
     *
     * @return string
     */
    public function getJsLayout()
    {
        foreach ($this->layoutProcessors as $processor) {
            $this->jsLayout = $processor->process($this->jsLayout);
        }
        return \Zend_Json::encode($this->jsLayout);
    }

    /**
     * Get Category Id By Name
     *
     * @return int|false
     */
    public function getCategoryIdByName($categoryName)
    {
        $category = $this->categoryFactory->create()->loadByAttribute('name', $categoryName);

        if ($category->getId()) {
            return $category->getId();
        }

        return false;
    }

    /**
     * Whether redirect to cart enabled
     *
     * @return bool
     */
    public function isRedirectToCartEnabled()
    {
        return $this->_scopeConfig->getValue(
            'checkout/cart/redirect_to_cart',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve current view mode
     *
     * @return string
     */
    public function getMode()
    {
        if ($this->getChildBlock('toolbar')) {
            return $this->getChildBlock('toolbar')->getCurrentMode();
        }

        // default Toolbar when the toolbar layout is not used
        $defaultToolbar = $this->listProduct->getToolbarBlock();
        $availableModes = $defaultToolbar->getModes();

        // layout config mode
        $mode = $this->getData('mode');

        if (!$mode || !isset($availableModes[$mode])) {
            // default config mode
            $mode = $defaultToolbar->getCurrentMode();
        }

        return $mode;
    }

    /**
     * Retrieve additional blocks html
     *
     * @return string
     */
    public function getAdditionalHtml()
    {
        return $this->getChildHtml('additional');
    }

    /**
     * Retrieve list toolbar HTML
     *
     * @return string
     */
    public function getToolbarHtml()
    {
        return $this->getChildHtml('toolbar');
    }
}
