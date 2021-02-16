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

use Magento\Catalog\Helper\Image;
use Magento\Catalog\Model\CategoryRepository;
use Magento\Catalog\Model\Rss\Category as RssCategoryModel;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultFactory;

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
     * ProductListing constructor
     *
     * @param Context $context
     * @param Image $imageHelper
     * @param CategoryRepository $categoryRepository
     * @param RssCategoryModel $rssCategoryModel
     */
    public function __construct(
        Context $context,
        Image $imageHelper,
        CategoryRepository $categoryRepository,
        RssCategoryModel $rssCategoryModel
    ) {
        parent::__construct($context);
        $this->imageHelper = $imageHelper;
        $this->categoryRepository = $categoryRepository;
        $this->rssCategoryModel = $rssCategoryModel;
    }

    /**
     * Execute action based on categoryId request and return product collection
     */
    public function execute()
    {
        /** @var Json $response */
        $response = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        if ($categoryId = $this->getRequest()->getParam('categoryId')) {
            $category = $this->categoryRepository->get($categoryId);

            $storeId = $category->getStoreId();

            $productCollection = [];
            foreach ($this->rssCategoryModel->getProductCollection($category, $storeId) as $product) {
                $productCollection[] = [
                    'entity_id' => $product->getId(),
                    'sku' => $product->getSku(),
                    'name' => $product->getName(),
                    'price' => $product->getPrice(),
                    'src' => $this->imageHelper->init($product, 'product_base_image')->getUrl(),
                    'url' => $product->getProductUrl()
                ];
            }

            $response->setData($productCollection);
        }

        return $response;
    }
}
