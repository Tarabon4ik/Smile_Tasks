<?php
/**
 * Admin Controller Save
 *
 * @category  Smile
 * @package   Smile\Manifestation
 * @author    Taras Trubaichuk <taras.trubaichuk@smile-ukraine.com>
 * @copyright 2021 Smile
 */
declare(strict_types=1);

namespace Smile\Manifestation\Controller\Adminhtml\Manifestation;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Smile\Manifestation\Api\Data\ManifestationInterface;
use Smile\Manifestation\Api\Data\PlaceInterface;
use Smile\Manifestation\Api\Data\ManifestationPlaceLinkInterface;
use Smile\Manifestation\Model\ManifestationFactory;
use Smile\Manifestation\Model\ManifestationPlaceLinkFactory;
use Smile\Manifestation\Model\ManifestationPlaceLinkRepository;
use Smile\Manifestation\Model\ManifestationRepository;
use Smile\Manifestation\Model\ResourceModel\ManifestationPlaceLink;

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
     * Manifestation Data from AdminForm
     */
    const GENERAL_DATA = 'general';
    const ADDITIONAL_DATA = 'additional';
    const PLACES_DATA = 'places';
    const ASSIGNED_PLACES_DATA = 'assigned_places';

    /**
     * @var ManifestationRepository
     */
    protected $manifestationRepository;

    /**
     * @var ManifestationFactory
     */
    protected $manifestationFactory;

    /**
     * @var ManifestationPlaceLinkRepository
     */
    protected $manifestationPlaceLinkRepository;

    /**
     * @var ManifestationPlaceLinkFactory
     */
    protected $manifestationPlaceLinkFactory;

    /**
     * @var ManifestationPlaceLink
     */
    protected $placeLink;

    /**
     * Date Time
     *
     * @var DateTime
     */
    protected $dateTime;

    /**
     * @var SearchCriteriaBuilderFactory
     */
    protected $searchCriteriaBuilderFactory;

    /**
     * @param Context $context
     * @param ManifestationRepository $manifestationRepository
     * @param ManifestationFactory $manifestationFactory
     * @param ManifestationPlaceLinkRepository $manifestationPlaceLinkRepository
     * @param ManifestationPlaceLinkFactory $manifestationPlaceLinkFactory
     * @param ManifestationPlaceLink $placeLink
     * @param DateTime $dateTime
     * @param SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     */
    public function __construct(
        Context $context,
        ManifestationRepository $manifestationRepository,
        ManifestationFactory $manifestationFactory,
        ManifestationPlaceLinkRepository $manifestationPlaceLinkRepository,
        ManifestationPlaceLinkFactory $manifestationPlaceLinkFactory,
        ManifestationPlaceLink $placeLink,
        DateTime $dateTime,
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
    ) {
        parent::__construct($context);
        $this->manifestationRepository = $manifestationRepository;
        $this->manifestationFactory = $manifestationFactory;
        $this->manifestationPlaceLinkRepository = $manifestationPlaceLinkRepository;
        $this->manifestationPlaceLinkFactory = $manifestationPlaceLinkFactory;
        $this->placeLink = $placeLink;
        $this->dateTime = $dateTime;
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
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
        $manifestationId = $requestData[self::GENERAL_DATA][ManifestationInterface::MANIFESTATION_ID];

        if (!$manifestationId) {
            $manifestation = $this->manifestationFactory->create();
        } else {
            $manifestation = $this->manifestationRepository->getById((int)$manifestationId);
        }

        $manifestationData = $requestData[self::GENERAL_DATA];

        $manifestation->setTitle($manifestationData[ManifestationInterface::TITLE]);
        $manifestation->setDescription($manifestationData[ManifestationInterface::DESCRIPTION]);
        $manifestation->setStartDate($manifestationData[ManifestationInterface::START_DATE]);
        $manifestation->setEndDate($manifestationData[ManifestationInterface::END_DATE]);
        $manifestation->setIsNeedWater((bool)$manifestationData[ManifestationInterface::IS_NEED_WATER]);
        $manifestation->setIsNeedElectricity((bool)$manifestationData[ManifestationInterface::IS_NEED_ELECTRICITY]);

        $manifestation->setUpdatedAt($this->dateTime->gmtDate());

        if (!empty($requestData[self::ADDITIONAL_DATA])) {
            $additionalData = $requestData[self::ADDITIONAL_DATA];

            $manifestation->setMetaTitle($additionalData[ManifestationInterface::META_TITLE]);
            $manifestation->setMetaDescription($additionalData[ManifestationInterface::META_DESCRIPTION]);
        }

        $this->manifestationRepository->save($manifestation);

        if (!$manifestationId) {
            $manifestationId = (int)$manifestation->getIdByTitle($requestData[self::GENERAL_DATA][ManifestationInterface::TITLE]);
        }

        if (!empty($requestData[self::PLACES_DATA])) {
            $places = $requestData[self::PLACES_DATA][self::ASSIGNED_PLACES_DATA];

            $placesId = [];
            foreach ($places as $place) {
                if ($place[PlaceInterface::PLACE_ID] != null) {
                    $placesId[$place[PlaceInterface::PLACE_ID]] = $place[PlaceInterface::PLACE_ID];
                }
            }

            /** @var SearchCriteriaBuilder $searchCriteriaBuilder */
            $searchCriteriaBuilder = $this->searchCriteriaBuilderFactory->create();
            $searchCriteria = $searchCriteriaBuilder
                ->addFilter(ManifestationPlaceLinkInterface::MANIFESTATION_ID, $manifestationId)
                ->create();

            $links = $this->manifestationPlaceLinkRepository->getList($searchCriteria)->getItems();

            /** @var ManifestationPlaceLinkInterface $link */
            foreach ($links as $link) {
                $links[$link->getPlaceId()] = $link->getLinkId();
            }

            foreach ($placesId as $placeId) {
                $model = isset($links[$placeId])
                    ? $links[$placeId]
                    : null;
                if (!$model) {
                    $linkModel = $this->manifestationPlaceLinkFactory->create();
                    $linkModel->setPlaceId($placeId);
                    $linkModel->setManifestationId($manifestationId);
                    $this->manifestationPlaceLinkRepository->save($linkModel);
                }
            }
        }

        $this->messageManager->addSuccessMessage(__('The Manifestation has been saved.'));
        $this->processRedirectAfterSuccessSave($resultRedirect, $manifestationId);

        return $resultRedirect;
    }

    /**
     * @param Redirect $resultRedirect
     * @param int $manifestationId
     *
     * @return void
     */
    private function processRedirectAfterSuccessSave(Redirect $resultRedirect, $manifestationId)
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
    private function processRedirectAfterFailureSave(Redirect $resultRedirect, $manifestationId = null)
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
