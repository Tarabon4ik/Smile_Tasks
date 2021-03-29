<?php

declare(strict_types=1);

namespace Smile\Manifestation\Controller\Adminhtml\Place;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Smile\Manifestation\Api\Data\PlaceInterface;
use Smile\Manifestation\Api\Data\PlaceInterfaceFactory;
use Smile\Manifestation\Api\PlaceRepositoryInterface;

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
     * Place Data from AdminForm
     */
    const GENERAL_DATA = 'general';
    const CONTACT_DATA = 'contact_info';
    const ADDRESS_DATA = 'address';

    /**
     * @var PlaceInterfaceFactory
     */
    private $placeFactory;

    /**
     * @var PlaceRepositoryInterface
     */
    private $placeRepository;

    /**
     * Date Time
     *
     * @var DateTime
     */
    protected $dateTime;

    /**
     * @param Context $context
     * @param PlaceInterfaceFactory $placeFactory
     * @param PlaceRepositoryInterface $placeRepository
     * @param DateTime $dateTime
     */
    public function __construct(
        Context $context,
        PlaceInterfaceFactory $placeFactory,
        PlaceRepositoryInterface $placeRepository,
        DateTime $dateTime
    ) {
        parent::__construct($context);
        $this->placeFactory = $placeFactory;
        $this->placeRepository = $placeRepository;
        $this->dateTime = $dateTime;
    }

    /**
     * @inheritdoc
     */
    public function execute(): ResultInterface
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $request = $this->getRequest();
        $requestData = $request->getParams();
        if (!$request->isPost() || empty($requestData[self::GENERAL_DATA])) {
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
        $placeId = $requestData[self::GENERAL_DATA][PlaceInterface::PLACE_ID];

        if (!$placeId) {
            $place = $this->placeFactory->create();
        } else {
            $place = $this->placeRepository->getById((int)$placeId);
        }

        // Set general data
        $placeData = $requestData[self::GENERAL_DATA];

        $place->setName($placeData[PlaceInterface::NAME]);
        $place->setDescription($placeData[PlaceInterface::DESCRIPTION]);
        $place->setEnabled((bool)$placeData[PlaceInterface::ENABLED]);

        // Set contact data
        $contactData = $requestData[self::CONTACT_DATA];

        $place->setContactName($contactData[PlaceInterface::CONTACT_NAME]);
        $place->setEmail($contactData[PlaceInterface::EMAIL]);
        $place->setPhone($contactData[PlaceInterface::PHONE]);

        // Set address data
        $addressData = $requestData[self::ADDRESS_DATA];

        $place->setLatitude((float)$addressData[PlaceInterface::LATITUDE]);
        $place->setLongitude((float)$addressData[PlaceInterface::LONGITUDE]);
        $place->setCountryId($addressData[PlaceInterface::COUNTRY_ID]);
        $place->setRegionId($addressData[PlaceInterface::REGION_ID] ? $addressData[PlaceInterface::REGION_ID] : null);
        $place->setRegion($addressData[PlaceInterface::REGION]);
        $place->setCity($addressData[PlaceInterface::CITY]);
        $place->setStreet($addressData[PlaceInterface::STREET]);

        $place->setUpdatedAt($this->dateTime->gmtDate());

        $this->placeRepository->save($place);

        $this->messageManager->addSuccessMessage(__('The Place has been saved.'));
        $this->processRedirectAfterSuccessSave($resultRedirect, $placeId);

        return $resultRedirect;
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
