<?php

declare(strict_types=1);

namespace Smile\Manifestation\Controller\Adminhtml\Place;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Smile\Manifestation\Api\Data\PlaceInterface;
use Smile\Manifestation\Api\PlaceRepositoryInterface;

/**
 * Edit Controller
 */
class Edit extends Action implements HttpGetActionInterface
{
    /**
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Smile_Manifestation::manifestation_place';

    /**
     * @var PlaceRepositoryInterface
     */
    private $placeRepository;

    /**
     * @param Context $context
     * @param PlaceRepositoryInterface $placeRepository
     */
    public function __construct(
        Context $context,
        PlaceRepositoryInterface $placeRepository
    ) {
        parent::__construct($context);
        $this->placeRepository = $placeRepository;
    }

    /**
     * @inheritdoc
     */
    public function execute(): ResultInterface
    {
        $placeId = $this->getRequest()->getParam(PlaceInterface::PLACE_ID);
        try {
            $place = $this->placeRepository->getById($placeId);

            /** @var Page $result */
            $result = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
            $result->setActiveMenu(self::ADMIN_RESOURCE)
                ->addBreadcrumb(__('Edit Place'), __('Edit Place'));
            $result->getConfig()
                ->getTitle()
                ->prepend(__('Edit Place: %name', ['name' => $place->getName()]));
        } catch (NoSuchEntityException $e) {
            /** @var Redirect $result */
            $result = $this->resultRedirectFactory->create();
            $this->messageManager->addErrorMessage(
                __('Place with ID "%value" does not exist.', ['value' => $placeId])
            );
            $result->setPath('*/*');
        }

        return $result;
    }
}
