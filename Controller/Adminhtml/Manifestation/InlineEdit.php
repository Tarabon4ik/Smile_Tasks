<?php
declare(strict_types=1);

namespace Smile\Manifestation\Controller\Adminhtml\Manifestation;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Validation\ValidationException;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Smile\Manifestation\Api\Data\ManifestationInterface;
use Smile\Manifestation\Api\ManifestationRepositoryInterface;

/**
 * InlineEdit Controller
 */
class InlineEdit extends Action implements HttpPostActionInterface
{
    /**
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Smile_Manifestation::manifestation';

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var ManifestationRepositoryInterface
     */
    private $manifestationRepository;

    /**
     * @param Context $context
     * @param DataObjectHelper $dataObjectHelper
     * @param ManifestationRepositoryInterface $manifestationRepository
     */
    public function __construct(
        Context $context,
        DataObjectHelper $dataObjectHelper,
        ManifestationRepositoryInterface $manifestationRepository
    ) {
        parent::__construct($context);
        $this->dataObjectHelper = $dataObjectHelper;
        $this->manifestationRepository = $manifestationRepository;
    }

    /**
     * @inheritdoc
     */
    public function execute(): ResultInterface
    {
        $errorMessages = [];
        $request = $this->getRequest();
        $requestData = $request->getParam('items', []);

        if ($request->isXmlHttpRequest() && $request->isPost() && $requestData) {
            foreach ($requestData as $itemData) {
                try {
                    $manifestationId = (int)$itemData[ManifestationInterface::MANIFESTATION_ID];
                    $manifestation = $this->manifestationRepository->getById($manifestationId);
                    $this->dataObjectHelper->populateWithArray($manifestation, $itemData, ManifestationInterface::class);
                    $this->manifestationRepository->save($manifestation);
                } catch (NoSuchEntityException $e) {
                    $errorMessages[] = __(
                        '[ID: %value] The Manifestation does not exist.',
                        ['value' => $manifestationId]
                    );
                } catch (ValidationException $e) {
                    foreach ($e->getErrors() as $localizedError) {
                        $errorMessages[] = __('[ID: %value] %message', [
                            'value' => $manifestationId,
                            'message' => $localizedError->getMessage()
                        ]);
                    }
                } catch (CouldNotSaveException $e) {
                    $errorMessages[] = __('[ID: %value] %message', [
                        'value' => $manifestationId,
                        'message' => $e->getMessage()
                    ]);
                }
            }
        } else {
            $errorMessages[] = __('Please correct the sent data.');
        }

        /** @var Json $resultJson */
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData([
            'messages' => $errorMessages,
            'error' => count($errorMessages),
        ]);

        return $resultJson;
    }
}
