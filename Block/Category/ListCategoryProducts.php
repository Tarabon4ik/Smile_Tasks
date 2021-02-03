<?php
namespace Smile\Catalog\Block\Category;

use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Template;

class ListCategoryProducts extends Template
{
    /**
     * @var array|\Magento\Checkout\Block\Checkout\LayoutProcessorInterface[]
     */
    protected $layoutProcessors;

    /**
     * @var CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @var CategoryFactory
     */
    protected $categoryFactory;

    /**
     * @var Json
     */
    protected $jsonHelper;

    /**
     *
     */
    protected $_category;

    /**
     *
     */
    protected $objectManager;

    public function __construct(
        Template\Context $context,
        CollectionFactory $productCollectionFactory,
        CategoryFactory $categoryFactory,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        Json $jsonHelper,
        array $layoutProcessors = [],
        array $data = []
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->categoryFactory = $categoryFactory;
        $this->jsonHelper = $jsonHelper;
        $this->objectManager = $objectManager;
        $this->jsLayout = isset($data['jsLayout']) && is_array($data['jsLayout']) ? $data['jsLayout'] : [];
        $this->layoutProcessors = $layoutProcessors;
        parent::__construct($context, $data);
    }

    public function getJsLayout()
    {
        foreach ($this->layoutProcessors as $processor) {
            $this->jsLayout = $processor->process($this->jsLayout);
        }
        return \Zend_Json::encode($this->jsLayout);
    }

    public function getCategoryIdByName($categoryName)
    {
        $collection = $this->categoryFactory->create()->getCollection()
            ->addAttributeToFilter('name', $categoryName)->setPageSize(1);

        if ($collection->getSize()) {
            $categoryId = $collection->getFirstItem()->getId();
        }

        return $categoryId;
    }

    public function getCurrentCategory()
    {
        $category = $this->objectManager->get('Magento\Framework\Registry')->registry('current_category');

        return $category;
    }

    /**
     * Get category object
     *
     * @return \Magento\Catalog\Model\Category
     */
    public function getCategory($categoryId)
    {
        $this->_category = $this->categoryFactory->create();
        $this->_category->load($categoryId);

        return $this->_category;
    }

    /**
     * Get all children categories IDs
     *
     * @param boolean $asArray return result as array instead of comma-separated list of IDs
     * @return array|string
     */
    public function getAllChildren($asArray = false, $categoryId = false)
    {
        if ($this->_category) {
            return $this->_category->getAllChildren($asArray);
        } else {
            return $this->getCategory($categoryId)->getAllChildren($asArray);
        }
    }

    public function getProductCollection($category_id_array)
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect('*')
            ->addCategoriesFilter(['in' => $category_id_array])
            ->addAttributeToFilter('visibility', \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH)
            ->addAttributeToFilter('status', \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
            ->setPageSize(10);

        return $collection;
    }

    public function getProductCollectionJsonById($categoryId)
    {
        $category_id_array = $this->getAllChildren(true, $categoryId);

        $productCollection = [];

        foreach ($this->getProductCollection($category_id_array) as $product) {
            array_push($productCollection, $product->getData());
        }

        return $this->jsonHelper->serialize($productCollection);
    }
}
