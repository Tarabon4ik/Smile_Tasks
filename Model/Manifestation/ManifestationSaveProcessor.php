<?php

declare(strict_types=1);

namespace Smile\Manifestation\Model\Manifestation;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\EntityManager\EventManager;
use Smile\Manifestation\Api\Data\ManifestationInterface;
use Smile\Manifestation\Api\Data\ManifestationInterfaceFactory;
use Smile\Manifestation\Api\ManifestationRepositoryInterface;

/**
 * Save manifestation processor for save manifestation controller
 */
class ManifestationSaveProcessor
{
    /**
     * @var ManifestationInterfaceFactory
     */
    private $manifestationFactory;

    /**
     * @var ManifestationRepositoryInterface
     */
    private $manifestationRepository;

    /**
     * @var ManifestationPlaceLinkProcessor
     */
    private $manifestationPlaceLinkProcessor;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * @param ManifestationInterfaceFactory $manifestationFactory
     * @param ManifestationRepositoryInterface $manifestationRepository
     * @param ManifestationPlaceLinkProcessor $manifestationPlaceLinkProcessor
     * @param DataObjectHelper $dataObjectHelper
     * @param EventManager $eventManager
     */
    public function __construct(
        ManifestationInterfaceFactory $manifestationFactory,
        ManifestationRepositoryInterface $manifestationRepository,
        ManifestationPlaceLinkProcessor $manifestationPlaceLinkProcessor,
        DataObjectHelper $dataObjectHelper,
        EventManager $eventManager
    ) {
        $this->manifestationFactory = $manifestationFactory;
        $this->manifestationRepository = $manifestationRepository;
        $this->manifestationPlaceLinkProcessor = $manifestationPlaceLinkProcessor;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->eventManager = $eventManager;
    }

    /**
     * Save manifestation process action
     *
     * @param int|null $manifestationId
     * @param RequestInterface $request
     * @return int
     */
    public function process($manifestationId, RequestInterface $request): int
    {
        if (null === $manifestationId) {
            $manifestation = $this->manifestationFactory->create();
        } else {
            $manifestation = $this->manifestationRepository->getById($manifestationId);
        }
        $requestData = $request->getParams();
        $this->dataObjectHelper->populateWithArray($manifestation, $requestData['general'], ManifestationInterface::class);
        $this->eventManager->dispatch(
            'controller_action_manifestation_populate_manifestation_with_data',
            [
                'request' => $request,
                'manifestation' => $manifestation,
            ]
        );
        $manifestationId = $this->manifestationRepository->save($manifestation)->getId();
        $this->eventManager->dispatch(
            'save_manifestation_controller_processor_after_save',
            [
                'request' => $request,
                'manifestation' => $manifestation,
            ]
        );

        $assignedPlaces =
            isset($requestData['places']['assigned_places'])
            && is_array($requestData['places']['assigned_places'])
                ? $this->prepareAssignedPlaces($requestData['places']['assigned_places'])
                : [];
        $this->manifestationPlaceLinkProcessor->process($manifestationId, $assignedPlaces);

        return $manifestationId;
    }

    /**
     * Convert built-in UI component property position into priority
     *
     * @param array $assignedPlaces
     * @return array
     */
    private function prepareAssignedPlaces(array $assignedPlaces): array
    {
        foreach ($assignedPlaces as $key => $manifestation) {
            if (empty($manifestation['priority'])) {
                $manifestation['priority'] = (int) $manifestation['position'];
                $assignedPlaces[$key] = $manifestation;
            }
        }
        return $assignedPlaces;
    }
}
