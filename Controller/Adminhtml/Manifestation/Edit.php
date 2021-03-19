<?php

declare(strict_types=1);

namespace Smile\Manifestation\Controller\Adminhtml\Manifestation;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Smile\Manifestation\Api\Data\ManifestationInterface;
use Smile\Manifestation\Api\ManifestationRepositoryInterface;

/**
 * Edit Controller
 */
class Edit extends Action implements HttpGetActionInterface
{
    /**
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Smile_Manifestation::manifestation';

    /**
     * @var ManifestationRepositoryInterface
     */
    private $manifestationRepository;

    /**
     * @param Context $context
     * @param ManifestationRepositoryInterface $manifestationRepository
     */
    public function __construct(
        Context $context,
        ManifestationRepositoryInterface $manifestationRepository
    ) {
        parent::__construct($context);
        $this->manifestationRepository = $manifestationRepository;
    }

    /**
     * @inheritdoc
     */
    public function execute(): ResultInterface
    {
        $manifestationId = (int)$this->getRequest()->getParam(ManifestationInterface::MANIFESTATION_ID);
        try {
            $manifestation = $this->manifestationRepository->getById($manifestationId);

            /** @var Page $result */
            $result = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
            $result->setActiveMenu(self::ADMIN_RESOURCE)
                ->addBreadcrumb(__('Edit Manifestation'), __('Edit Manifestation'));
            $result->getConfig()
                ->getTitle()
                ->prepend(__('Edit Manifestation: %title', ['title' => $manifestation->getTitle()]));
        } catch (NoSuchEntityException $e) {
            /** @var Redirect $result */
            $result = $this->resultRedirectFactory->create();
            $this->messageManager->addErrorMessage(
                __('Manifestation with id "%value" does not exist.', ['value' => $manifestationId])
            );
            $result->setPath('*/*');
        }

        return $result;
    }
}
