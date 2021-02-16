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

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface as ProductRepository;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\CategoryRepository;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Rss\Category as RssCategoryModel;
use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use Magento\Checkout\Helper\Cart;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Url\Helper\Data;
use Magento\Framework\View\Element\Template;

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
     * Category Repository
     *
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * Rss Category Model
     *
     * @var RssCategoryModel
     */
    protected $rssCategoryModel;

    /**
     * @var Cart
     */
    protected $cartHelper;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var Data
     */
    protected $urlHelper;

    /**
     * ListCategoryProducts constructor
     *
     * @param Template\Context $context
     * @param Json $jsonHelper,
     * @param CategoryFactory $categoryFactory
     * @param CategoryRepository $categoryRepository
     * @param RssCategoryModel $rssCategoryModel
     * @param array $layoutProcessors
     * @param array $data
     * @param Cart $cartHelper
     * @param ProductRepository $productRepository
     * @param Data $urlHelper
     */
    public function __construct(
        Template\Context $context,
        Json $jsonHelper,
        CategoryFactory $categoryFactory,
        CategoryRepository $categoryRepository,
        RssCategoryModel $rssCategoryModel,
        Cart $cartHelper,
        ProductRepository $productRepository,
        Data $urlHelper,
        array $layoutProcessors = [],
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->categoryFactory = $categoryFactory;
        $this->categoryRepository = $categoryRepository;
        $this->rssCategoryModel = $rssCategoryModel;
        $this->jsonHelper = $jsonHelper;
        $this->jsLayout = isset($data['jsLayout']) && is_array($data['jsLayout']) ? $data['jsLayout'] : [];
        $this->layoutProcessors = $layoutProcessors;
        $this->cartHelper = $cartHelper;
        $this->productRepository = $productRepository;
        $this->urlHelper = $urlHelper;
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
     * Get Product Collection By Category Id
     *
     * @return array|null
     */
    public function getProductCollectionByCategoryId($categoryId)
    {
        $category = $this->categoryRepository->get($categoryId);

        $storeId = $category->getStoreId();

        $productCollection = [];
        foreach ($this->rssCategoryModel->getProductCollection($category, $storeId) as $product) {
            $productCollection[] = [
                'sku' => $product->getSku()
            ];
        }

        return $productCollection;
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
     * Retrieve product instance by sku
     *
     * @param string $sku
     * @return ProductInterface
     */
    public function getProductBySku($sku)
    {
        return $this->productRepository->get($sku);
    }

    /**
     * Retrieve url for direct adding product to cart
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param array $additional
     * @return string
     */
    public function getAddToCartUrl($product, $additional = [])
    {
        return $this->cartHelper->getAddUrl($product, $additional);
    }

    /**
     * Get post parameters
     *
     * @param Product $product
     * @return array
     */
    public function getAddToCartPostParams(Product $product)
    {
        $url = $this->getAddToCartUrl($product, ['_escape' => false]);
        return [
            'action' => $url,
            'data' => [
                'product' => (int) $product->getEntityId(),
                ActionInterface::PARAM_NAME_URL_ENCODED => $this->urlHelper->getEncodedUrl($url),
            ]
        ];
    }
}
