<?php

namespace Smile\Onepage\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;
use Smile\Manifestation\Helper\Image;
use Smile\Manifestation\Model\Manifestation;
use Smile\Manifestation\Model\ManifestationRepository;

/**
 * Class ManifestationListing
 */
class ManifestationListing extends Action
{
    /**
     * Image
     *
     * @var Image
     */
    protected $imageHelper;

    /**
     * Manifestation Repository
     *
     * @var ManifestationRepository
     */
    protected $manifestationRepository;

    /**
     * @var JsonSerializer
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
     * @param ManifestationRepository $manifestationRepository
     * @param PostHelper $postHelper
     * @param JsonSerializer|null $serializer
     */
    public function __construct(
        Context $context,
        Image $imageHelper,
        ManifestationRepository $manifestationRepository,
        PostHelper $postHelper,
        JsonSerializer $serializer = null
    ) {
        parent::__construct($context);
        $this->imageHelper = $imageHelper;
        $this->manifestationRepository = $manifestationRepository;
        $this->postHelper = $postHelper;
        $this->serializer = $serializer ?: \Magento\Framework\App\ObjectManager::getInstance()
            ->get(JsonSerializer::class);
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

        $manifestationList = $this->manifestationRepository->getList()->getItems();

        $productCollection = [];
        /** @var Manifestation $manifestation */
        foreach ($manifestationList as $manifestation) {
            $productCollection[] = [
                'manifestation_id' => (int) $manifestation->getId(),
                'title' => $manifestation->getTitle(),
                'description' => $manifestation->getDescription(),
                'qty' => $manifestation->getQty(),
                'is_available' => $manifestation->getQty() != null ? 1 : 0,
                'price' => $manifestation->getPrice(),
                'src' => $this->imageHelper->init($manifestation, 'image')->getUrl(),
                'url' => $manifestation->getManifestationUrl(),
                'post_data' => $this->postHelper->getPostData($manifestation->getManifestationUrl(), ['manifestation' => $manifestation->getId()])
            ];
        }

        $response->setData($productCollection);

        return $response;
    }
}
