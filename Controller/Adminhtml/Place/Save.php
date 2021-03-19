<?php

declare(strict_types=1);

namespace Smile\Manifestation\Controller\Adminhtml\Place;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Validation\ValidationException;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Smile\Manifestation\Api\Data\PlaceInterface;
use Smile\Manifestation\Api\Data\PlaceInterfaceFactory;
use Smile\Manifestation\Api\PlaceRepositoryInterface;
use Smile\Manifestation\Model\Place\PlaceHydrator;

/**
 * Place save controller.
 */
class Save extends Action implements HttpPostActionInterface
{
    /**
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Smile_Manifestation::manifestation_place';

    /**
     * @var PlaceInterfaceFactory
     */
    private $placeFactory;

    /**
     * @var PlaceRepositoryInterface
     */
    private $placeRepository;

    /**
     * @var PlaceHydrator
     */
    private $placeHydrator;

    /**
     * @param Context $context
     * @param PlaceInterfaceFactory $placeFactory
     * @param PlaceRepositoryInterface $placeRepository
     * @param PlaceHydrator $placeHydrator
     */
    public function __construct(
        Context $context,
        PlaceInterfaceFactory $placeFactory,
        PlaceRepositoryInterface $placeRepository,
        PlaceHydrator $placeHydrator
    ) {
        parent::__construct($context);
        $this->placeFactory = $placeFactory;
        $this->placeRepository = $placeRepository;
        $this->placeHydrator = $placeHydrator;
    }

    /**
     * @inheritdoc
     */
    public function execute(): ResultInterface
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $request = $this->getRequest();
        $requestData = $request->getPost()->toArray();

        if (!$request->isPost() || empty($requestData['general'])) {
            $this->messageManager->addErrorMessage(__('Wrong request.'));
            $this->processRedirectAfterFailureSave($resultRedirect);
            return $resultRedirect;
        }
        $placeId = $requestData['general'][PlaceInterface::PLACE_ID];
        try {
            $place = $this->placeRepository->getById($placeId);
            if ($place->getStreet() !== $requestData['general'][PlaceInterface::STREET]) {
                unset($requestData['general'][PlaceInterface::LATITUDE]);
                unset($requestData['general'][PlaceInterface::LONGITUDE]);
                $place->setLatitude(null);
                $place->setLongitude(null);
            }
        } catch (NoSuchEntityException $e) {
            $place = $this->placeFactory->create();
        }
        try {
            $this->processSave($place, $requestData);
            $this->messageManager->addSuccessMessage(__('The Place has been saved.'));
            $this->processRedirectAfterSuccessSave($resultRedirect, $place->getId());
        } catch (ValidationException $e) {
            foreach ($e->getErrors() as $localizedError) {
                $this->messageManager->addErrorMessage($localizedError->getMessage());
            }
            $this->_session->setPlaceFormData($requestData);
            $this->processRedirectAfterFailureSave($resultRedirect, $placeId);
        } catch (CouldNotSaveException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $this->_session->setPlaceFormData($requestData);
            $this->processRedirectAfterFailureSave($resultRedirect, $placeId);
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage(__('Could not save Place.'));
            $this->_session->setPlaceFormData($requestData);
            $this->processRedirectAfterFailureSave($resultRedirect, $placeId);
        }
        return $resultRedirect;
    }

    /**
     * Hydrate data from request and save place.
     *
     * @param PlaceInterface $place
     * @param array $requestData
     * @return void
     * @throws CouldNotSaveException
     * @throws ValidationException
     */
    private function processSave(PlaceInterface $place, array $requestData)
    {
        $place = $this->placeHydrator->hydrate($place, $requestData);

        $this->_eventManager->dispatch(
            'controller_action_manifestation_populate_place_with_data',
            [
                'request' => $this->getRequest(),
                'place' => $place,
            ]
        );

        $this->placeRepository->save($place);

        $this->_eventManager->dispatch(
            'controller_action_manifestation_place_save_after',
            [
                'request' => $this->getRequest(),
                'place' => $place,
            ]
        );
    }

    /**
     * Get redirect url after place save.
     *
     * @param Redirect $resultRedirect
     * @param $placeId
     * @return void
     */
    private function processRedirectAfterSuccessSave(Redirect $resultRedirect, $placeId)
    {
        if ($this->getRequest()->getParam('back')) {
            $resultRedirect->setPath(
                '*/*/edit',
                [
                    PlaceInterface::PLACE_ID => $placeId,
                    '_current' => true,
                ]
            );
        } elseif ($this->getRequest()->getParam('redirect_to_new')) {
            $resultRedirect->setPath(
                '*/*/new',
                [
                    '_current' => true,
                ]
            );
        } else {
            $resultRedirect->setPath('*/*/');
        }
    }

    /**
     * Get redirect url after unsuccessful place save
     *
     * @param Redirect $resultRedirect
     * @param int|null $placeId
     * @return void
     */
    private function processRedirectAfterFailureSave(Redirect $resultRedirect, $placeId = null)
    {
        if (null === $placeId) {
            $resultRedirect->setPath('*/*/new');
        } else {
            $resultRedirect->setPath(
                '*/*/edit',
                [
                    PlaceInterface::PLACE_ID => $placeId,
                    '_current' => true,
                ]
            );
        }
    }
}
