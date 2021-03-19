<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Smile\Manifestation\Controller\Adminhtml\Manifestation;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Validation\ValidationException;
use Smile\Manifestation\Api\Data\ManifestationInterface;
use Smile\Manifestation\Model\Manifestation\ManifestationSaveProcessor;
use Magento\Framework\App\Action\HttpPostActionInterface;

/**
 * Save Controller
 */
class Save extends Action implements HttpPostActionInterface
{
    /**
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Smile_Manifestation::manifestation';

    /**
     * @var ManifestationSaveProcessor
     */
    private $manifestationSaveProcessor;

    /**
     * @param Context $context
     * @param ManifestationSaveProcessor $manifestationSaveProcessor
     */
    public function __construct(
        Context $context,
        ManifestationSaveProcessor $manifestationSaveProcessor
    ) {
        parent::__construct($context);
        $this->manifestationSaveProcessor = $manifestationSaveProcessor;
    }

    /**
     * @inheritdoc
     */
    public function execute(): ResultInterface
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $request = $this->getRequest();
        $requestData = $request->getParams();
        if (!$request->isPost() || empty($requestData['general'])) {
            $this->messageManager->addErrorMessage(__('Wrong request.'));
            $this->processRedirectAfterFailureSave($resultRedirect);
            return $resultRedirect;
        }
        return $this->processSave($requestData, $request, $resultRedirect);
    }

    /**
     * @param array $requestData
     * @param RequestInterface $request
     * @param Redirect $resultRedirect
     * @return ResultInterface
     */
    private function processSave(
        array $requestData,
        RequestInterface $request,
        Redirect $resultRedirect
    ): ResultInterface {
        try {
            $manifestationId = isset($requestData['general'][ManifestationInterface::MANIFESTATION_ID])
                ? (int)$requestData['general'][ManifestationInterface::MANIFESTATION_ID]
                : null;

            $manifestationId = $this->manifestationSaveProcessor->process($manifestationId, $request);

            $this->messageManager->addSuccessMessage(__('The Manifestation has been saved.'));
            $this->processRedirectAfterSuccessSave($resultRedirect, $manifestationId);
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__('The Manifestation does not exist.'));
            $this->processRedirectAfterFailureSave($resultRedirect);
        } catch (ValidationException $e) {
            foreach ($e->getErrors() as $localizedError) {
                $this->messageManager->addErrorMessage($localizedError->getMessage());
            }
            $this->processRedirectAfterFailureSave($resultRedirect, $manifestationId);
        } catch (CouldNotSaveException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $this->processRedirectAfterFailureSave($resultRedirect, $manifestationId);
        } catch (InputException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $this->processRedirectAfterFailureSave($resultRedirect, $manifestationId);
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage(__('Could not save Manifestation.'));
            $this->processRedirectAfterFailureSave($resultRedirect, $manifestationId ?? null);
        }
        return $resultRedirect;
    }

    /**
     * @param Redirect $resultRedirect
     * @param int $manifestationId
     *
     * @return void
     */
    private function processRedirectAfterSuccessSave(Redirect $resultRedirect, int $manifestationId)
    {
        if ($this->getRequest()->getParam('back')) {
            $resultRedirect->setPath('*/*/edit', [
                ManifestationInterface::MANIFESTATION_ID => $manifestationId,
                '_current' => true,
            ]);
        } elseif ($this->getRequest()->getParam('redirect_to_new')) {
            $resultRedirect->setPath('*/*/new', [
                '_current' => true,
            ]);
        } else {
            $resultRedirect->setPath('*/*/');
        }
    }

    /**
     * @param Redirect $resultRedirect
     * @param int|null $manifestationId
     *
     * @return void
     */
    private function processRedirectAfterFailureSave(Redirect $resultRedirect, int $manifestationId = null)
    {
        if (null === $manifestationId) {
            $resultRedirect->setPath('*/*/new');
        } else {
            $resultRedirect->setPath('*/*/edit', [
                ManifestationInterface::MANIFESTATION_ID => $manifestationId,
                '_current' => true,
            ]);
        }
    }
}
