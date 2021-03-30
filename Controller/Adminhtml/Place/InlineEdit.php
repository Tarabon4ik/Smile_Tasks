<?php
/**
 * Admin Controller InlineEdit
 *
 * @category  Smile
 * @package   Smile\Manifestation
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */
declare(strict_types=1);

namespace Smile\Manifestation\Controller\Adminhtml\Place;

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
use Smile\Manifestation\Model\Place\PlaceCoordinatesDataProcessor;
use Smile\Manifestation\Api\Data\PlaceInterface;
use Smile\Manifestation\Api\PlaceRepositoryInterface;

/**
 * InlineEdit Controller
 */
class InlineEdit extends Action implements HttpPostActionInterface
{
    /**
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Smile_Manifestation::manifestation_place';

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var PlaceRepositoryInterface
     */
    private $placeRepository;

    /**
     * @var PlaceCoordinatesDataProcessor
     */
    private $placeCoordinatesDataProcessor;

    /**
     * @param Context $context
     * @param DataObjectHelper $dataObjectHelper
     * @param PlaceRepositoryInterface $placeRepository
     * @param PlaceCoordinatesDataProcessor $placeCoordinatesDataProcessor
     */
    public function __construct(
        Context $context,
        DataObjectHelper $dataObjectHelper,
        PlaceRepositoryInterface $placeRepository,
        PlaceCoordinatesDataProcessor $placeCoordinatesDataProcessor
    ) {
        parent::__construct($context);
        $this->dataObjectHelper = $dataObjectHelper;
        $this->placeRepository = $placeRepository;
        $this->placeCoordinatesDataProcessor = $placeCoordinatesDataProcessor;
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
                    $placeId = $itemData[PlaceInterface::PLACE_ID];
                    $itemData = $this->placeCoordinatesDataProcessor->execute($itemData);
                    $place = $this->placeRepository->getById($placeId);
                    $this->dataObjectHelper->populateWithArray($place, $itemData, PlaceInterface::class);
                    $this->placeRepository->save($place);
                } catch (NoSuchEntityException $e) {
                    $errorMessages[] = __(
                        '[ID: %value] The Place does not exist.',
                        ['value' => $placeId]
                    );
                } catch (ValidationException $e) {
                    foreach ($e->getErrors() as $localizedError) {
                        $errorMessages[] = __('[ID: %value] %message', [
                            'value' => $placeId,
                            'message' => $localizedError->getMessage()
                        ]);
                    }
                } catch (CouldNotSaveException $e) {
                    $errorMessages[] = __(
                        '[ID: %value] %message',
                        [
                            'value' => $placeId,
                            'message' => $e->getMessage()
                        ]
                    );
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
