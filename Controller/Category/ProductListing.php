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
use Magento\Catalog\Model\CategoryRepository;
use Magento\Catalog\Model\Rss\Category as RssCategoryModel;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultFactory;
use Magento\Wishlist\Model\Rss\Wishlist;
use Magento\Catalog\Helper\Product\Compare;
use Magento\Framework\Data\Helper\PostHelper;

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
     * Rss Category Model
     *
     * @var RssCategoryModel
     */
    protected $rssCategoryModel;

    /**
     * @var ListCompare
     */
    protected $listCompare;

    /**
     * @var ListProduct
     */
    protected $listProduct;

    /**
     * @var Wishlist
     */
    protected $wishlist;

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
     * ProductListing constructor
     *
     * @param Context $context
     * @param Image $imageHelper
     * @param CategoryRepository $categoryRepository
     * @param RssCategoryModel $rssCategoryModel,
     * @param ListCompare $listCompare
     * @param ListProduct $listProduct
     * @param Wishlist $wishlist
     * @param Compare $compare
     * @param \Magento\Framework\Serialize\Serializer\Json|null $serializer
     * @param PostHelper $postHelper
     */
    public function __construct(
        Context $context,
        Image $imageHelper,
        CategoryRepository $categoryRepository,
        RssCategoryModel $rssCategoryModel,
        ListCompare $listCompare,
        ListProduct $listProduct,
        Wishlist $wishlist,
        Compare $compare,
        PostHelper $postHelper,
        \Magento\Framework\Serialize\Serializer\Json $serializer = null
    ) {
        parent::__construct($context);
        $this->imageHelper = $imageHelper;
        $this->categoryRepository = $categoryRepository;
        $this->rssCategoryModel = $rssCategoryModel;
        $this->listCompare = $listCompare;
        $this->listProduct = $listProduct;
        $this->wishlist = $wishlist;
        $this->compare = $compare;
        $this->postHelper = $postHelper;
        $this->serializer = $serializer ?: \Magento\Framework\App\ObjectManager::getInstance()
            ->get(\Magento\Framework\Serialize\Serializer\Json::class);
    }

    /**
     * Execute action based on categoryId request and return product collection
     */
    public function execute()
    {
//        if ($this->_request->getParam('_')) {
//            echo 'pashol nahui';
//            return $this->resultRedirectFactory->create()->setUrl($this->_redirect->getRedirectUrl());
//        }

        /** @var Json $response */
        $response = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        if ($categoryId = $this->getRequest()->getParam('categoryId')) {
            $category = $this->categoryRepository->get($categoryId);

            $storeId = $category->getStoreId();

            $productCollection = [];
            foreach ($this->rssCategoryModel->getProductCollection($category, $storeId) as $product) {

                $productCollection[] = [
                    'product_id' => (int) $product->getEntityId(),
                    'sku' => $product->getSku(),
                    'short_description' => $product->getShortDescription(),
                    'is_saleable' => $product->isSaleable(),
                    'is_available' => $product->isAvailable(),
                    'name' => $product->getName(),
                    'price' => $this->wishlist->getProductPriceHtml($product),
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
