<?php

namespace Smile\Catalog\Model\Category;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

/**
 * Category Product model.
 */
class Product
{
    /**
     * @var Layer
     */
    protected $catalogLayer;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var Visibility
     */
    protected $visibility;

    /**
     * @param Resolver $layerResolver
     * @param CollectionFactory $collectionFactory
     * @param Visibility $visibility
     */
    public function __construct(
        Resolver $layerResolver,
        CollectionFactory $collectionFactory,
        Visibility $visibility
    ) {
        $this->catalogLayer = $layerResolver->get();
        $this->collectionFactory = $collectionFactory;
        $this->visibility = $visibility;
    }

    /**
     * Get products for given category.
     *
     * @param Category $category
     * @param int $storeId
     * @return Collection
     */
    public function getProductCollection(Category $category, $storeId)
    {
        /** @var $layer Layer */
        $layer = $this->catalogLayer->setStore($storeId);
        $collection = $category->getResourceCollection();
        $collection->addAttributeToSelect('url_key')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('is_anchor')
            ->addAttributeToFilter('is_active', 1)
            ->addIdFilter($category->getChildren())
            ->load();
        /** @var $productCollection Collection */
        $productCollection = $this->collectionFactory->create();

        $currentCategory = $layer->setCurrentCategory($category);
        $layer->prepareProductCollection($productCollection);
        $productCollection->addCountToCategories($collection);

        $category->getProductCollection()->setStoreId($storeId);

        $products = $currentCategory->getProductCollection()
            ->addAttributeToSort('updated_at', 'desc')
            ->setVisibility($this->visibility->getVisibleInCatalogIds())
            ->setCurPage(1);

        return $products;
    }
}
