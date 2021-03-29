<?php

declare(strict_types=1);

namespace Smile\Manifestation\Controller\Adminhtml\Manifestation;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Smile\Manifestation\Api\Data\ManifestationInterface;
use Smile\Manifestation\Api\ManifestationRepositoryInterface;

/**
 * Delete Controller
 */
class Delete extends Action implements HttpPostActionInterface
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
        $resultRedirect = $this->resultRedirectFactory->create();

        $manifestationId = $this->getRequest()->getPost(ManifestationInterface::MANIFESTATION_ID);
        if ($manifestationId === null) {
            $this->messageManager->addErrorMessage(__('Wrong request.'));
            return $resultRedirect->setPath('*/*');
        }

        try {
            $manifestationId = (int)$manifestationId;
            $this->manifestationRepository->deleteById($manifestationId);
            $this->messageManager->addSuccessMessage(__('The Manifestation has been deleted.'));
            $resultRedirect->setPath('*/*');
        } catch (CouldNotDeleteException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $resultRedirect->setPath('*/*/edit', [
                ManifestationInterface::MANIFESTATION_ID => $manifestationId,
                '_current' => true,
            ]);
        }

        return $resultRedirect;
    }
}
