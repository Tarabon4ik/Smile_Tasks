<?php
/**
 * Controller ProductListing
 *
 * @category  Smile
 * @package   Smile\Catalog
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */

namespace Smile\Catalog\Controller\Category;

use Magento\Catalog\Block\Product\Compare\ListCompare;
use Magento\Catalog\Block\Product\ListProduct;
use Magento\Catalog\Helper\Image;
use Magento\Catalog\Helper\Product\Compare;
use Magento\Catalog\Model\CategoryRepository;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Data\Helper\PostHelper;
use Smile\Catalog\Model\Category\Product as CategoryProducts;
use Smile\Catalog\ViewModel\Product\Listing as ProductListingViewModel;

/**
 * Class ProductListing
 */
class ProductListing extends Action
{
    /**
     * Image
     *
     * @var Image
     */
    protected $imageHelper;

    /**
     * Category Repository
     *
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * @var ListCompare
     */
    protected $listCompare;

    /**
     * @var ListProduct
     */
    protected $listProduct;

    /**
     * @var Compare
     */
    protected $compare;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $serializer;

    /**
     * @var PostHelper
     */
    protected $postHelper;

    /**
     * @var CategoryProducts
     */
    protected $categoryProducts;

    /**
     * @var ProductListingViewModel
     */
    protected $productListing;

    /**
     * ProductListing constructor
     *
     * @param Context $context
     * @param Image $imageHelper
     * @param CategoryRepository $categoryRepository
     * @param ListCompare $listCompare
     * @param ListProduct $listProduct
     * @param Compare $compare
     * @param CategoryProducts $categoryProducts
     * @param \Magento\Framework\Serialize\Serializer\Json|null $serializer
     * @param PostHelper $postHelper
     * @param ProductListingViewModel $productListing
     */
    public function __construct(
        Context $context,
        Image $imageHelper,
        CategoryRepository $categoryRepository,
        ListCompare $listCompare,
        ListProduct $listProduct,
        Compare $compare,
        PostHelper $postHelper,
        CategoryProducts $categoryProducts,
        ProductListingViewModel $productListing,
        \Magento\Framework\Serialize\Serializer\Json $serializer = null
    ) {
        parent::__construct($context);
        $this->imageHelper = $imageHelper;
        $this->categoryRepository = $categoryRepository;
        $this->listCompare = $listCompare;
        $this->listProduct = $listProduct;
        $this->compare = $compare;
        $this->postHelper = $postHelper;
        $this->categoryProducts = $categoryProducts;
        $this->productListing = $productListing;
        $this->serializer = $serializer ?: \Magento\Framework\App\ObjectManager::getInstance()
            ->get(\Magento\Framework\Serialize\Serializer\Json::class);
    }

    /**
     * Execute action based on categoryId request and return product collection
     */
    public function execute()
    {
        if (!$this->getRequest()->isAjax()) {
            return $this->resultRedirectFactory->create()->setUrl($this->_redirect->getRefererUrl());
        }

        /** @var Json $response */
        $response = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        if ($categoryId = $this->getRequest()->getParam('categoryId')) {
            $category = $this->categoryRepository->get((int) $categoryId);

            $storeId = $category->getStoreId();

            $productCollection = [];
            foreach ($this->categoryProducts->getProductCollection($category, $storeId) as $product) {
                $productCollection[] = [
                    'product_id' => (int) $product->getEntityId(),
                    'sku' => $product->getSku(),
                    'short_description' => $product->getShortDescription(),
                    'is_saleable' => $product->isSaleable(),
                    'is_available' => $product->isAvailable(),
                    'name' => $product->getName(),
                    'price' => $this->productListing->getProductPriceHtml($product),
                    'src' => $this->imageHelper->init($product, 'product_base_image')->getUrl(),
                    'url' => $product->getProductUrl(),
                    'post_data' => $this->postHelper->getPostData($this->listProduct->getAddToCartUrl($product), ['product' => $product->getEntityId()]),
                    'add_to_wishlist_params' => $this->listCompare->getAddToWishlistParams($product),
                    'add_to_compare_params' => $this->compare->getPostDataParams($product),
                ];
            }

            $response->setData($productCollection);
        }

        return $response;
    }
}
